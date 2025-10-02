<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Exports\AdjustmentExport;
use App\Interfaces\InterfaceClass;
use App\Models\adjust_reasons;
use App\Models\adjustment_details;
use App\Models\adjustment_headers;
use App\Models\Location;
use App\Models\MovementType;
use App\Models\ProductCategory;
use App\Models\Profile;
use App\Models\Site;
use App\Models\Status;
use App\Models\Stock;
use App\Models\StockBooking;
use App\Models\StockMovement;
use App\Models\UserSite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AdjustmentController extends Controller
{
    public function listAdjustmentPage() : View
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::ADJUSTMENT_LIST
        )) {
            return abort(403, 'Insufficient permission.');
        }

        return view('list-adjustments');
    }

    public function formAdjustmentPage() : View
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::ADJUSTMENT_CREATE
        )) {
            return abort(403, 'Insufficient permission.');
        }

        return view('form-adjustments');
    }

    public function getProductData(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'site_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $fromSiteId = $validated['site_id'];

        $sql = ("SELECT DISTINCT pc.id AS product_id, pc.catg_code, pc.catg_name
            FROM stocks s, product_categories pc
            WHERE s.catg_id = pc.id
                AND s.site_id = $fromSiteId
                AND pc.flag = 1
            ORDER BY pc.catg_name");
		$data = DB::select($sql);

        return response()->json($data);

    }

    public function getLocation(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'catg_id' => ['required', 'integer'],
            'site_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $CatgId = $validated['catg_id'];
        $SiteId = $validated['site_id'];

        $sql = ("SELECT DISTINCT s.id AS site_id, s.site_code, s.location_id, s.location_code, l.location_name, s.catg_code
            FROM stocks s, locations l
            WHERE s.catg_id = $CatgId
                AND s.site_id = $SiteId
                AND s.location_id = l.id
                AND l.flag = 1
            ORDER BY s.site_code");
		$data = DB::select($sql);

        return response()->json($data);

    }

    public function getReason(Request $request) {
        $data = adjust_reasons::all();
        return $data;
    }

    /**
     * GET request for getting stock qty for adjustment request
     */
    public function getRecQtyUnit(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'site_id' => ['required', 'integer'],
            'product_id' => ['required', 'integer'],
            'location_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $siteId = $validated['site_id'];
        $productId = $validated['product_id'];
        $locationId = $validated['location_id'];

        $dataStock = Stock::where('site_id', $siteId)->where('catg_id', $productId)->where('location_id', $locationId)->first();

        $stockBooking = StockBooking::where('site_id', $siteId)->where('catg_id', $productId)->where('location_id', $locationId)->first();

        (array) $data = [
            'data_stock' => $dataStock,
            'stock_booking' => $stockBooking,
        ];

        return response()->json($data);
    }

    public function getRecUpdQty(Request $request) {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'reasonData' => ['required', 'integer'],
            'qtyData' => ['required', 'integer'],
            'stockQtyData' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $reason = adjust_reasons::where('id', $validated['reasonData'])->first();
        $operator = $reason->default_operator;

        $stockQty = (int)$validated['stockQtyData'];
        $qtyData = (int)$validated['qtyData'];

        $updateStock = $stockQty + (($operator == '-' ? $qtyData * -1 : $qtyData));

        // if ($reason-> == 1) {
        //     $updateStock = $validated['stockQtyData'] - $validated['qtyData'];
        // } else if ($validated['reasonData'] == 2) {
        //     $updateStock = $validated['stockQtyData'] + $validated['qtyData'];
        // }
        return response()->json($updateStock);
    }


    public function postAdjReqSubmit(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'adjustment_date' => ['required'],
            'site' => ['required', 'integer'],
            'detail' => ['required', 'array'],
            'detail.*.product_id' => ['required', 'integer'],
            'detail.*.location_id' => ['required', 'integer'],
            'detail.*.reason_id' => ['required', 'integer', 'min:1'],
            'detail.*.stock_qty_id' => ['required', 'integer'],
            'detail.*.qty_id' => ['required', 'integer', 'min:1', 'max:10000'],
            'detail.*.update_qty_id' => ['required', 'integer', 'min:0'],
        ]);


        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();


        // return response()->json($validated);

        /** Get status flag value */
        $statusAdj = Status::where('module', 'adjustment')->where('flag_value', 1)->first()->flag_value;
        // $statusReceived = Status::where('module', 'transfer')->where('flag_value', 3)->first()->flag_value;

        /** Get sites */
        $site = Site::where('id', $validated['site'])->first();

        /** Prepare transaction number, Format: TRF/MM.YY/STORE_CODE/SEQ */
        $adjustmentDate = Carbon::createFromFormat('d/m/Y', $validated['adjustment_date'], 'Asia/Jakarta')->setTimezone('Asia/Jakarta');
        $adjDateMonthYear = Carbon::parse($adjustmentDate)->setTimezone('Asia/Jakarta')->format('m.y');
        $prefixAdjNumber = 'ADJ/'.$adjDateMonthYear.'/'.$site->store_code.'/';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(adj_no,3), '999')),0) AS no FROM adjustment_headers WHERE adj_no LIKE '$prefixAdjNumber%'");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }

        $adjNumber = $prefixAdjNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        // return response()->json($adjNumber);

        DB::beginTransaction();
        try {
            /** Insert transfer header */
            $adjHeader = adjustment_headers::create([
                'adj_no' => $adjNumber,
                'adj_date' => $adjustmentDate,
                'site_id' => $site->id,
                'site_code' => $site->site_code,
                'flag' => $statusAdj,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            /** Looping details */
            foreach ($validated['detail'] as $detail) {

                $productCategory = ProductCategory::where('id', $detail['product_id'])->first();
                $location = Location::where('id', $detail['location_id'])->first();
                $stockQty = $detail['stock_qty_id'];
                $adjQty = $detail['qty_id'];
                $updateQty = $detail['update_qty_id'];
                $unit = $productCategory->unit;
                $reason = adjust_reasons::where('id', $detail['reason_id'])->first();

                // BELOM PASTI
                $move_code = MovementType::where('mov_code', 'ADJ')->first();

                /** Get stock data */
                $dataStock = Stock::where('site_id', $site->id)->where('location_id', $location->id)->where('catg_id', $productCategory->id)->first();

                /** Check stock is freeze or not */
                if ($dataStock->so_flag == 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit adjustment, stock product '.$productCategory->catg_name.' is freeze.']);
                }

                /** Check product is active or not */
                if ($productCategory->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit adjustment, product '.$productCategory->catg_name.' is not active.']);
                }

                /** Check location is active or not */
                if ($location->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit adjustment, location '.$location->location_name.' is not active.']);
                }

                /** Insert transfer detail */
                adjustment_details::create([
                    'adj_id' => $adjHeader->id,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'catg_desc' => $productCategory->catg_name,
                    'adj_qty' => ($reason->default_operator == '-' ? $adjQty * -1 : $adjQty),
                    'stock_before_adj' => $stockQty,
                    'stock_after_adj' => $updateQty,
                    'unit' => $unit,
                    'reason_code' => $reason->reason_code,
                    'location_id' => $location->id,
                    'location_code' => $location->location_code,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                $dataStock->quantity = $updateQty;
                $dataStock->updated_at = date('Y-m-d H:i:s');
                $dataStock->updated_by = $user?->id;
                $dataStock->save();

                // MOVEMENT BELOM
                StockMovement::create([
                    'mov_date' => $adjustmentDate,
                    'site_id' => $site->id,
                    'site_code' => $site->site_code,
                    'location_id' => $location->id,
                    'location_code' => $location->location_code,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'quantity' => ($reason->default_operator == '-' ? $adjQty * -1 : $adjQty),
                    'unit' => $unit,
                    'mov_code' => $move_code->mov_code,
                    'purch_price' => 0,
                    'sales_price' => 0,
                    'ref_no' => $adjNumber,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);


            }

            (string) $title = 'Success';
            (string) $message = 'Adjustment request successfully submitted with number: '.$adjNumber;
            (array) $data = [
                'trx_number' => $adjNumber,
            ];
            (string) $route = route('list-adjustments');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit adjustment request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit adjustment request', 422, $e);
        }

    }


    public function getAdjListTable(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'adj_no' => ['nullable', 'string'],
            'site' => ['nullable', 'string'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        // return response()->json($validated);

        /** Get status id */
        $statusAdj = Status::where('module', 'adjustment')->where('flag_value', 1)->first()->flag_value;

        /** Get sites permission */
        $userSites = UserSite::where('user_id', $user?->id)->get()->pluck('site_code')->toArray();

        /** Prepare for parameters */
        $params = '';
        if (! is_null($validated['from_date'])) {
            $params .= " AND ah.adj_date >= '".Carbon::parse($validated['from_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($validated['to_date'])) {
            $params .= " AND ah.adj_date <= '".Carbon::parse($validated['to_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($validated['adj_no'])) {
            $params .= " AND ah.adj_no ILIKE '%".$validated['adj_no']."%'";
        }
        if (! is_null($validated['site'])) {
            $params .= " AND CAST(st.site_code AS TEXT) ILIKE '%".$validated['site']."%'";
        }
        // if (! is_null($validated['to_site'])) {
        //     $params .= " AND CAST(dest.site_code AS TEXT) ILIKE '%".$validated['to_site']."%'";
        // }
        // if (! is_null($validated['status_id'])) {
        //     $params .= " AND s.flag_value = ".$validated['status_id'];
        // }

        $sql = ("SELECT ah.id AS adj_id, ah.adj_no, ah.adj_date,
                st.site_code AS site_code, st.store_code AS store_code, st.site_description AS site_desc,
                s.flag_desc AS status, s.flag_value
            FROM adjustment_headers ah, sites st, status s
            WHERE ah.site_id = st.id
                AND ah.flag = s.flag_value
                AND s.module = 'adjustment'
                AND EXISTS (
                    SELECT 1
                    FROM user_sites us
                    WHERE us.user_id = $user->id
                        AND (us.site_id = st.id)
                )$params
            ORDER BY ah.adj_date DESC");
		$data = DB::select($sql);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("actions", function($row) use($statusAdj) {
                $buttons = '<a href="/view-adjustment/'.$row->adj_id.'" title="View">
                    <button type="submit" class="btn btn-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                    </button>
                </a>';


                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function viewAdjustmentPage($id): View
    {
        $user = Auth::user();

        /** Get status id */
        $statusRecSubmit = Status::where('module', 'adjustment')->where('flag_value', 1)->first()->flag_value;

        // Get adjustment header data
        $sqlHeader = "SELECT ah.id, ah.adj_no, ah.adj_date, sites.site_code, sites.store_code, sites.site_description
            FROM adjustment_headers ah, sites
            WHERE ah.site_id = sites.id
                AND ah.id = $id
            LIMIT 1";

        $sqlDetail = "SELECT ad.id, ad.catg_id, ad.catg_code, ad.catg_desc, ad.adj_qty, ad.stock_before_adj, ad.stock_after_adj, ad.unit, ar.reason_desc, l.location_name
        FROM adjustment_details ad, adjust_reasons ar, locations l
        WHERE ad.adj_id = $id
            AND ad.reason_code = ar.reason_code
            AND ad.location_id = l.id
        ORDER BY ad.catg_desc";

        (array) $data = [
            'adj_id' => $id,
            'adj_header_data' => collect(DB::select($sqlHeader))->first(),
            'adj_detail_data' => DB::select($sqlDetail),
        ];
        return view('view-adjustments', $data);

    }


    public function exportExcelListAdjustment(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'adj_no' => ['nullable', 'string'],
            'site' => ['nullable', 'string'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }

        return Excel::download(new AdjustmentExport($request), 'list_adjusment.xlsx');
    }
}
