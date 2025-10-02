<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Exports\ReturnExport;
use App\Interfaces\InterfaceClass;
use App\Models\Location;
use App\Models\MovementType;
use App\Models\ProductCategory;
use App\Models\Profile;
use App\Models\ReturnDetail;
use App\Models\ReturnHeader;
use App\Models\Site;
use App\Models\Status;
use App\Models\Stock;
use App\Models\StockBooking;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\UserSite;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReturnController extends Controller
{
    public function listReturnPage() {

        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::RETURN_LIST
        )) {
            return abort(403, 'Insufficient permission.');
        }

        return view('list-return');

    }

    public function getRetListDatatable(Request $request)
    {

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'ret_date' => ['nullable', 'date'],
            'ret_no' => ['nullable', 'string'],
            'supp_site' => ['nullable', 'string']
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusRetPendingApprove = Status::where('module', 'return')->where('flag_value', 2)->first()->flag_value;

        /** Get sites permission */
        $userSites = UserSite::where('user_id', $user?->id)->get()->pluck('site_code')->toArray();

        /** Prepare for parameters */
        $params = '';

        if (! is_null($validated['ret_date'])) {
            $params .= " AND rh.ret_date = '".Carbon::parse($validated['ret_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }

        if (! is_null($validated['ret_no'])) {
            $params .= " AND rh.ret_no ILIKE '%".$validated['ret_no']."%'";
        }

        if (! is_null($validated['supp_site'])) {
            $params .= " AND (CAST(rh.location_code AS TEXT) ILIKE '%".$validated['supp_site']."%'
                OR rh.supp_name ILIKE '%".$validated['supp_site']."%'
            )";
        }

        $sql = ("SELECT rh.id AS ret_id, rh.ret_no, rh.ret_date, sites.store_code, sites.site_code, rh.supp_name,
                (CASE WHEN rh.location_id IS NOT NULL THEN rh.location_code
                    ELSE rh.supp_name END) AS location,
                s.flag_desc AS status, s.flag_value
        FROM return_headers rh LEFT JOIN sites ON sites.id = rh.site_id, status s
        WHERE rh.flag = s.flag_value
            AND s.module = 'return'
            AND EXISTS (
                SELECT 1
                FROM user_sites us
                WHERE us.user_id = $user->id
                    AND (us.site_id = sites.id)
            )$params
            ORDER BY rh.ret_date DESC");

		$data = DB::select($sql);
        $canApprove = Profile::authorize(InterfaceClass::RETURN_APPROVAL);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("actions", function($row) use($statusRetPendingApprove, $userSites, $canApprove) {
                $buttons = '<a href="/view-return/'.$row->ret_id.'" title="View">
                    <button type="submit" class="btn btn-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                    </button>
                </a>';

                // Profile::authorize(InterfaceClass::EXPENDING_APPROVAL)

                if ($row->flag_value == $statusRetPendingApprove && $row->supp_name != null && in_array($row->site_code, $userSites) && $canApprove) {
                    $buttons .= '<a href="/approve-return/'.$row->ret_id.'" title="Approve">
                        <button type="submit" class="btn" id="btn-approve-list">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-check" viewBox="0 0 16 16">
                                <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
                                <path d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-12Z"/>
                                <path d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3Z"/>
                            </svg>
                        </button>
                    </a>';
                }

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function viewReturnPage($id): View
    {
        $user = Auth::user();

        $sqlRetType = ReturnHeader::where('id', $id)->first();

        $sqlHeader = "";

        if ( $sqlRetType->location_id != null ) {
            $sqlType = "Internal";

            $sqlHeader = "SELECT rh.ret_no, rh.ret_date, s.store_code, s.site_description, l.location_code, l.location_name, rh.note
                            FROM return_headers rh, sites s, locations l
                            WHERE rh.id = $id
                                AND rh.site_id = s.id
                                AND rh.location_id = l.id
                            LIMIT 1";

            $sqlDetail = "SELECT rd.id AS detail_id, rd.catg_id, rd.catg_code, rd.catg_desc,
                                rd.quantity, rd.unit, rd.location_id
                            FROM return_details rd
                            WHERE rd.ret_id = $id
                            ORDER BY rd.catg_desc";

        } else {
            $sqlType = "Supplier";

            $sqlHeader = "SELECT rh.ret_no, rh.ret_date, s.store_code, s.site_description, sup.supp_code, sup.supp_name, rh.note
                            FROM return_headers rh, sites s, suppliers sup
                            WHERE rh.id = $id
                                AND rh.site_id = s.id
                                AND rh.supp_code = sup.supp_code
                            LIMIT 1";

            $sqlDetail = "SELECT rd.id AS detail_id, rd.catg_id, rd.catg_code, rd.catg_desc,
                            rd.quantity, rd.unit, rd.location_id, l.location_code, l.location_name
                        FROM return_details rd, locations l
                        WHERE rd.ret_id = $id
                            AND rd.location_id = l.id
                        ORDER BY rd.catg_desc";
        }



        (array) $data = [
            'ret_id' => $id,
            'ret_header_data' => collect(DB::select($sqlHeader))->first(),
            'ret_type' => $sqlType,
            // 'from_sup_site' => $fromSupSite,
            'ret_detail_data' => DB::select($sqlDetail),
            // 'is_print_allowed' => $isPrintAllowed,
        ];

        return view('view-return', $data);
    }

    // Form Return
    public function formReturn() : View
    {

        if (!Profile::authorize(InterfaceClass::RETURN_CREATE)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = auth()->user()->id;

        // $dataSite = UserSite::where('user_id', $user)->orderBy('site_code', 'asc')->get();
        // $productCategories = ProductCategory::all();
        // $supplier = Supplier::all();

        return view('form-return', [
            // 'dataSites' => $dataSite,
            // 'productCategories' => $productCategories,
            // 'suppliers' => $supplier
        ]);
    }

    public function getRetAllLocation(){
        $location = Location::where('flag', 1)->get();
        return response()->json($location);
    }

    public function getRetProductList(Request $request){
        $product = Stock::where('site_id', $request->site_id)
                    ->join('product_categories', 'stocks.catg_id', '=', 'product_categories.id')
                    ->select('product_categories.id', 'product_categories.catg_code', 'product_categories.catg_name', 'product_categories.unit')
                    ->get();

        return response()->json($product);
    }

    public function getRetProductLocationList(Request $request){
        $productLocation = Stock::where('site_id', $request->site_id)->where('location_id', $request->location_id)
                    ->join('product_categories', 'stocks.catg_id', '=', 'product_categories.id')
                    ->select('product_categories.id', 'product_categories.catg_code', 'product_categories.catg_name', 'product_categories.unit')
                    ->get();

        return response()->json($productLocation);
    }


    public function getRetLocation(Request $request){
        $location = Stock::where('site_id', $request->site_id)->where('catg_id', $request->product_id)
                    ->join('locations', 'stocks.location_id', '=', 'locations.id')
                    ->select('locations.id','locations.location_code', 'locations.location_name')
                    ->get();

        return response()->json($location);
    }

    public function postRetReqSubmitSupp(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'returnDate' => ['required'],
            'type_id' => ['required', 'string'],
            'note' => ['required', 'string'],
            'site_id' => ['required', 'integer'],
            'supplier_id' => ['required', 'integer'],
            'detail' => ['required', 'array'],
            'detail.*.product_id' => ['required', 'integer'],
            'detail.*.location_id' => ['required', 'integer'],
            'detail.*.qty' => ['required', 'integer', 'min:1'],
        ]);


        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status flag value */
        $statusReturnPendingApproval = Status::where('module', 'return')->where('flag_value', 2)->first()->flag_value;

        $site = Site::where('id', $validated['site_id'])->first();
        $supplier = Supplier::where('id', $validated['supplier_id'])->first();

        /** Prepare transaction number, Format: TRF/MM.YY/STORE_CODE/SEQ */
        $returnDate = Carbon::createFromFormat('d/m/Y', $validated['returnDate'], 'Asia/Jakarta')->setTimezone('Asia/Jakarta');
        $retDateMonthYear = Carbon::parse($returnDate)->setTimezone('Asia/Jakarta')->format('m.y');
        $prefixRetNumber = 'RET/'.$retDateMonthYear.'/'.$site->store_code.'/';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(ret_no,3), '999')),0) AS no FROM return_headers WHERE ret_no LIKE '$prefixRetNumber%'");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }
        $retNumber = $prefixRetNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            /** Insert transfer header */
            $retHeader = ReturnHeader::create([
                'ret_no' => $retNumber,
                'ret_date' => $returnDate,
                'location_id' => null,
                'location_code' => null,
                'site_id' => $site->id,
                'site_code' => $site->site_code,
                'supp_code' => $supplier->supp_code,
                'supp_name' => $supplier->supp_name,
                'flag' => $statusReturnPendingApproval,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
                'note' => $validated['note'],
            ]);

            /** Looping details */
            foreach ($validated['detail'] as $detail) {

                $productCategory = ProductCategory::where('id', $detail['product_id'])->first();
                $location = Location::where('id', $detail['location_id'])->first();
                $quantity = (int)$detail['qty'];
                $unit = $productCategory->unit;

                $stock = Stock::where('site_id', $site->id)->where('catg_id', $productCategory->id)->where('location_id', $location->id)->first();

                // MovementType
                $move_code = MovementType::where('mov_code', 'RET')->first();

                /** Check stock is freeze or not */
                if ($stock->so_flag == 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit return, stock product '.$productCategory->catg_name.' is freeze.']);
                }

                /** Check product is active or not */
                if ($productCategory->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to receiving from supplier, product '.$productCategory->catg_name.' is not active.']);
                }

                /** Check location is active or not */
                if ($location->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to receiving from supplier, location '.$location->location_name.' is not active.']);
                }

                /** Insert transfer detail */
                ReturnDetail::create([
                    'ret_id' => $retHeader->id,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'catg_desc' => $productCategory->catg_name,
                    'unit_price' => 0,
                    'quantity' => ((int)$detail['qty'])*(-1),
                    'unit' => $unit,
                    'location_id' => $location->id,
                    'location_code' => $location->location_code,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                // Update Qty Stock
                $dataStock = Stock::where('site_id', $site->id)->where('location_id', $location->id)->where('catg_id', $productCategory->id)->first();

                $stockBookingSum = StockBooking::where('site_id', $site->id)->where('catg_id', $productCategory->id)->where('location_id', $location->id)->sum('quantity');


                if (($dataStock->quantity - $stockBookingSum) < $quantity) {
                    throw ValidationException::withMessages(['detail' => 'Stock '.$productCategory->catg_name.' is not available. Total Return Qty: '.$quantity.', Stock Qty: '.$dataStock->quantity.', Stock Booking Qty: '.$stockBookingSum]);
                }

                /** Add booking qty */
                StockBooking::create([
                    'site_id' => $site->id,
                    'site_code' => $site->site_code,
                    'location_id' => $location->id,
                    'location_code' => $location->location_code,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'quantity' => $detail['qty'],
                    'unit' => $productCategory->unit,
                    'book_type' => 'RET', /** Return */
                    'reference_no' => $retNumber,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                // $sisaStock = ((int)$dataStock->quantity) - ((int)$quantity);

                // $dataStock->quantity = $sisaStock;
                // $dataStock->updated_at = date('Y-m-d H:i:s');
                // $dataStock->updated_by = $user?->id;
                // $dataStock->save();



            }

            (string) $title = 'Success';
            (string) $message = 'Return request successfully submitted with number: '.$retNumber;
            (array) $data = [
                'ret_number' => $retNumber,
            ];
            (string) $route = route('list-return');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit return request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit return request', 422, $e);
        }
    }


    public function postRetReqSubmitinternal(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'returnDate' => ['required'],
            'type_id' => ['required', 'string'],
            'site_id' => ['required', 'integer'],
            'note' => ['required', 'string'],
            'location_id' => ['required', 'integer'],
            'detail' => ['required', 'array'],
            'detail.*.product_id' => ['required', 'integer'],
            'detail.*.qty' => ['required', 'integer', 'min:1'],
        ]);


        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();


        // return response()->json($validated);


        /** Get status flag value */
        $statusReturnFinished = Status::where('module', 'return')->where('flag_value', 1)->first()->flag_value;

        $site = Site::where('id', $validated['site_id'])->first();
        $location = Location::where('id', $validated['location_id'])->first();

        /** Prepare transaction number, Format: RET/MM.YY/STORE_CODE/SEQ */
        $returnDate = Carbon::createFromFormat('d/m/Y', $validated['returnDate'], 'Asia/Jakarta')->setTimezone('Asia/Jakarta');
        $retDateMonthYear = Carbon::parse($returnDate)->setTimezone('Asia/Jakarta')->format('m.y');
        $prefixRetNumber = 'RET/'.$retDateMonthYear.'/'.$site->store_code.'/';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(ret_no,3), '999')),0) AS no FROM return_headers WHERE ret_no LIKE '$prefixRetNumber%'");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }
        $retNumber = $prefixRetNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            /** Insert transfer header */
            $retHeader = ReturnHeader::create([
                'ret_no' => $retNumber,
                'ret_date' => $returnDate,
                'location_id' => $location->id,
                'location_code' => $location->location_code,
                'site_id' => $site->id,
                'site_code' => $site->site_code,
                'supp_code' => null,
                'supp_name' => null,
                'flag' => $statusReturnFinished,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
                'note' => $validated['note'],
            ]);

            /** Looping details */
            foreach ($validated['detail'] as $detail) {

                $productCategory = ProductCategory::where('id', $detail['product_id'])->first();
                $quantity = (int)$detail['qty'];
                $unit = $productCategory->unit;

                // MovementType
                $move_code = MovementType::where('mov_code', 'RET')->first();

                /** Check product is active or not */
                if ($productCategory->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to receiving from supplier, product '.$productCategory->catg_name.' is not active.']);
                }

                /** Insert transfer detail */
                ReturnDetail::create([
                    'ret_id' => $retHeader->id,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'catg_desc' => $productCategory->catg_name,
                    'unit_price' => 0,
                    'quantity' => $quantity,
                    'unit' => $unit,
                    'location_id' => null,
                    'location_code' => null,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                // Update Qty Stock
                $dataStock = Stock::where('site_id', $site->id)->where('location_id', $location->id)->where('catg_id', $productCategory->id)->first();

                $totalStock = ((int)$dataStock->quantity) + ((int)$quantity);

                $dataStock->quantity = $totalStock;
                $dataStock->updated_at = date('Y-m-d H:i:s');
                $dataStock->updated_by = $user?->id;
                $dataStock->save();

                // STOCK MOVEMENT
                StockMovement::create([
                    'mov_date' => $returnDate,
                    'site_id' => $site->id,
                    'site_code' => $site->site_code,
                    'location_id' => $location->id,
                    'location_code' => $location->location_code,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'quantity' => (int)$quantity,
                    'unit' => $unit,
                    'mov_code' => $move_code->mov_code,
                    'purch_price' => 0,
                    'sales_price' => 0,
                    'ref_no' => $retNumber,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

            }

            (string) $title = 'Success';
            (string) $message = 'Return request successfully submitted with number: '.$retNumber;
            (array) $data = [
                'ret_number' => $retNumber,
            ];
            (string) $route = route('list-return');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit return request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit return request', 422, $e);
        }
    }

    public function exportExcelListReturn(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'retNumber' => ['nullable', 'string'],
            'dateRet' => ['nullable', 'string'],
            'suppSite' => ['nullable', 'string'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }

        return Excel::download(new ReturnExport($request), 'list_return.xlsx');
    }


    public function approveReturnPage($id): View{
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::RETURN_APPROVAL)) {
            return abort(403, 'Insufficient permission.');
        }

        $sqlRetType = ReturnHeader::where('id', $id)->first();

        $sqlHeader = "";

        if ( $sqlRetType->location_id != null ) {
            $sqlType = "Internal";

            $sqlHeader = "SELECT rh.id, rh.ret_no, rh.ret_date, s.store_code, s.site_description, l.location_code, l.location_name, rh.note
                            FROM return_headers rh, sites s, locations l
                            WHERE rh.id = $id
                                AND rh.site_id = s.id
                                AND rh.location_id = l.id
                            LIMIT 1";

            $sqlDetail = "SELECT rd.id AS detail_id, rd.catg_id, rd.catg_code, rd.catg_desc,
                                rd.quantity, rd.unit, rd.location_id
                            FROM return_details rd
                            WHERE rd.ret_id = $id
                            ORDER BY rd.catg_desc";

        } else {
            $sqlType = "Supplier";

            $sqlHeader = "SELECT rh.id, rh.ret_no, rh.ret_date, s.store_code, s.site_description, sup.supp_code, sup.supp_name, rh.note
                            FROM return_headers rh, sites s, suppliers sup
                            WHERE rh.id = $id
                                AND rh.site_id = s.id
                                AND rh.supp_code = sup.supp_code
                            LIMIT 1";

            $sqlDetail = "SELECT rd.id AS detail_id, rd.catg_id, rd.catg_code, rd.catg_desc,
                            rd.quantity, rd.unit, rd.location_id, l.location_code, l.location_name
                        FROM return_details rd, locations l
                        WHERE rd.ret_id = $id
                            AND rd.location_id = l.id
                        ORDER BY rd.catg_desc";
        }

        (array) $data = [
            'ret_id' => $id,
            'ret_header_data' => collect(DB::select($sqlHeader))->first(),
            'ret_type' => $sqlType,
            'ret_detail_data' => DB::select($sqlDetail),
        ];

        return view('approve-return', $data);
    }

    public function postRetReqApprove(Request $request)
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::RETURN_APPROVAL)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'ret_header_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusRetPendingApprove = Status::where('module', 'return')->where('flag_value', 2)->first()->flag_value;
        $statusRetApprove = Status::where('module', 'return')->where('flag_value', 1)->first()->flag_value;

        /** Get return data */
        $retData = ReturnHeader::where('id', $validated['ret_header_id'])->first();

        if (is_null($retData)) {
            throw ValidationException::withMessages(['ret_header_id' => 'Return request not found']);
        }

        /** Check if status is pending approval */
        if ($retData->flag != $statusRetPendingApprove) {
            throw ValidationException::withMessages(['ret_header_id' => 'Return request status is not pending approve']);
        }

        // DB::beginTransaction();
        try {
            /** Get return detail */
            $retDetailData = ReturnDetail::where('ret_id', $retData->id)->get();
            foreach ($retDetailData as $detail) {
                /** Get stock data */
                $stock = Stock::where('site_id', $retData->site_id)->where('catg_id', $detail->catg_id)->where('location_id', $detail->location_id)->first();

                $productCategory = ProductCategory::where('id', $detail->catg_id)->first();

                /** Check stock is freeze or not */
                if ($stock->so_flag == 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to approve return, stock product '.$productCategory->catg_name.' is freeze.']);
                }

                /** Validate stock quantity */
                if ($stock->quantity < $detail->quantity) {
                    throw ValidationException::withMessages(['detail' => 'Stock is not available. Total Return Qty: '.$detail->quantity.', Stock Qty: '.$stock->quantity]);
                }
                $stock->quantity += $detail->quantity;
                $stock->save();

                /** Generate stock movement */
                StockMovement::create([
                    'mov_date' => date('Y-m-d'),
                    'site_id' => $retData->site_id,
                    'site_code' => $retData->site_code,
                    'location_id' => $detail->location_id,
                    'location_code' => $detail->location_code,
                    'catg_id' => $detail->catg_id,
                    'catg_code' => $detail->catg_code,
                    'quantity' => $detail->quantity,
                    'unit' => $detail->unit,
                    'mov_code' => 'RET',
                    'purch_price' => 0,
                    'sales_price' => 0,
                    'ref_no' => $retData->ret_no,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);
            }
            /** Remove booking qty */
            StockBooking::where('book_type', 'RET')->where('reference_no', $retData->ret_no)->delete();

            /** Update header */
            $retData->flag = $statusRetApprove;
            $retData->approved_by = $user?->id;
            $retData->approved_date = date('Y-m-d H:i:s');
            $retData->updated_by = $user?->id;
            $retData->save();

            (string) $title = 'Success';
            (string) $message = 'Return request successfully approved';
            (string) $route = route('list-return');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException("Failed request approval submit", 422, $e);
        }
    }


    public function postRetReqReject(Request $request)
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::RETURN_APPROVAL)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'ret_header_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusRetPendingApprove = Status::where('module', 'return')->where('flag_value', 2)->first()->flag_value;
        $statusRetReject = Status::where('module', 'return')->where('flag_value', 8)->first()->flag_value;

        /** Get return data */
        $retData = ReturnHeader::where('id', $validated['ret_header_id'])->first();

        if (is_null($retData)) {
            throw ValidationException::withMessages(['ret_header_id' => 'Return request not found']);
        }

        /** Check if status is pending approval */
        if ($retData->flag != $statusRetPendingApprove) {
            throw ValidationException::withMessages(['ret_header_id' => 'Return request status is not pending approve']);
        }

        // DB::beginTransaction();
        try {
            /** Update header */
            $retData->flag = $statusRetReject;
            $retData->updated_by = $user?->id;
            $retData->updated_at = date('Y-m-d H:i:s');
            $retData->save();

            /** Remove booking qty */
            StockBooking::where('book_type', 'RET')->where('reference_no', $retData->ret_no)->delete();

            (string) $title = 'Success';
            (string) $message = 'Return request successfully rejected';
            (string) $route = route('list-return');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException("Failed request rejected submit", 422, $e);
        }
    }
}
