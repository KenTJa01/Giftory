<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Exports\StockOpnameExport;
use App\Interfaces\InterfaceClass;
use App\Models\ExpendingHeader;
use App\Models\Location;
use App\Models\MovementType;
use App\Models\ProductCategory;
use App\Models\Profile;
use App\Models\Site;
use App\Models\Status;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\StockOpnameDetail;
use App\Models\StockOpnameHeader;
use App\Models\TransferHeader;
use App\Models\UserSite;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class StockOpnameController extends Controller
{
    /**
     * GET stock opname list page
     */
    public function listStockOpnamePage(Request $request): View
    {
        $user = Auth::user();

        return view('list-stock-opname');
    }

    /**
     * GET form stock opname page
     */
    public function formStockOpnamePage(Request $request): View
    {
        $user = Auth::user();

        return view('form-stock-opname');
    }

    /**
     * GET request for getting product location list
     */
    public function getStockOpnameProductLocationList(Request $request)
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

        $siteId = $validated['site_id'];

        $sql = ("SELECT DISTINCT l.id AS location_id, l.location_code, l.location_name
            FROM stocks s, locations l
            WHERE s.location_id = l.id
                AND s.site_id = $siteId
                AND l.flag = 1
            ORDER BY l.location_name");
		$data = DB::select($sql);

        return response()->json($data);
    }

    /**
     * GET request for getting product list
     */
    public function getStockOpnameProductList(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'site_id' => ['required', 'integer'],
            'location_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $siteId = $validated['site_id'];
        $locationId = $validated['location_id'];

        $sql = ("SELECT DISTINCT pc.id AS product_id, pc.catg_code, pc.catg_name, pc.unit
            FROM stocks s, product_categories pc
            WHERE s.catg_id = pc.id
                AND s.site_id = $siteId
                AND s.location_id = $locationId
                AND pc.flag = 1
            ORDER BY pc.catg_name");
		$data = DB::select($sql);

        return response()->json($data);
    }

    /**
     * POST request for submitting full stock opname
     */
    public function postStockOpnameFullSubmit(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            // 'so_date' => ['required', 'date_format:d/m/Y'],
            'so_date' => ['required'],
            'site' => ['required', 'integer'],
            'location' => ['required', 'integer'],
            'type' => ['required', 'string'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status flag value */
        $statusSoSubmit = Status::where('module', 'stock_opname')->where('flag_value', 0)->first()->flag_value;
        $statusSoFreeze = Status::where('module', 'stock_opname')->where('flag_value', 1)->first()->flag_value;
        $statusSoStockInput = Status::where('module', 'stock_opname')->where('flag_value', 2)->first()->flag_value;

        /** Get sites */
        $site = Site::where('id', $validated['site'])->first();

        /** Get location */
        $location = Location::where('id', $validated['location'])->first();

        /** Prepare transaction number, Format: SO/MM.YY/STORE_CODE/SEQ */
        $soDate = Carbon::createFromFormat('d/m/Y', $validated['so_date'], 'Asia/Jakarta')->setTimezone('Asia/Jakarta');
        $soDateMonthYear = Carbon::parse($soDate)->setTimezone('Asia/Jakarta')->format('m.y');
        $prefixTrxNumber = 'SO/'.$soDateMonthYear.'/'.$site->store_code.'/';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(so_no,3), '999')),0) AS no FROM stock_opname_headers WHERE so_no LIKE '$prefixTrxNumber%'");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }
        $trxNumber = $prefixTrxNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        /** Check already submitted stock opname or not */
        $countPendingSo = StockOpnameHeader::where('site_id', $site->id)->where('location_id', $location->id)
            ->where(function (Builder|QueryBuilder $query) use ($statusSoSubmit, $statusSoFreeze, $statusSoStockInput) {
                $query->where('flag', $statusSoSubmit)->orWhere('flag', $statusSoFreeze)->orWhere('flag', $statusSoStockInput);
            })->count();
        if ($countPendingSo > 0) {
            throw ValidationException::withMessages(['detail' => 'Failed to submit stock opname, there is still a process that has not been completed.']);
        }

        DB::beginTransaction();
        try {
            (int) $totalItem = 0;
            (int) $totalQty = 0;

            /** Insert stock opname header */
            $soHeader = StockOpnameHeader::create([
                'so_no' => $trxNumber,
                'so_date' => $soDate,
                'so_type' => $validated['type'],
                'site_id' => $site->id,
                'site_code' => $site->site_code,
                'location_id' => $location->id,
                'location_code' => $location->location_code,
                'total_items' => $totalItem,
                'total_qty' => $totalQty,
                'flag' => $statusSoSubmit,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            /** Looping all stocks */
            $stocks = Stock::where('site_id', $site->id)->where('location_id', $location->id)->get();
            foreach ($stocks as $stock) {
                /** Get product details */
                $product = ProductCategory::where('id', $stock->catg_id)->first();

                /** Check stock is freeze or not */
                if ($stock->so_flag == 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit stock opname, stock product '.$product->catg_name.' is used in another process.']);
                }

                /** Generate stock opname detail */
                StockOpnameDetail::create([
                    'so_id' => $soHeader->id,
                    'catg_id' => $stock->catg_id,
                    'catg_code' => $stock->catg_code,
                    'catg_desc' => $product->catg_name,
                    'before_quantity' => $stock->quantity,
                    'unit' => $stock->unit,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                $totalItem++;
                $totalQty += $stock->quantity;
            }

            /** Update total qty in headers */
            $soHeader->total_items = $totalItem;
            $soHeader->total_qty = $totalQty;
            $soHeader->save();

            (string) $title = 'Success';
            (string) $message = 'Full stock opname request successfully submitted with number: '.$trxNumber;
            (array) $data = [
                'trx_number' => $trxNumber,
            ];
            (string) $route = route('list-stock-opname');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit full stock opname request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit full stock opname request', 422, $e);
        }
    }

    /**
     * POST request for submitting partial stock opname
     */
    public function postStockOpnamePartialSubmit(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            // 'so_date' => ['required', 'date_format:d/m/Y'],
            'so_date' => ['required'],
            'site' => ['required', 'integer'],
            'location' => ['required', 'integer'],
            'type' => ['required', 'string'],
            'detail' => ['required', 'array'],
            'detail.*.product_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status flag value */
        $statusSoSubmit = Status::where('module', 'stock_opname')->where('flag_value', 0)->first()->flag_value;
        $statusSoFreeze = Status::where('module', 'stock_opname')->where('flag_value', 1)->first()->flag_value;
        $statusSoStockInput = Status::where('module', 'stock_opname')->where('flag_value', 2)->first()->flag_value;

        /** Get sites */
        $site = Site::where('id', $validated['site'])->first();

        /** Get location */
        $location = Location::where('id', $validated['location'])->first();

        /** Prepare transaction number, Format: SO/MM.YY/STORE_CODE/SEQ */
        $soDate = Carbon::createFromFormat('d/m/Y', $validated['so_date'], 'Asia/Jakarta')->setTimezone('Asia/Jakarta');
        $soDateMonthYear = Carbon::parse($soDate)->setTimezone('Asia/Jakarta')->format('m.y');
        $prefixTrxNumber = 'SO/'.$soDateMonthYear.'/'.$site->store_code.'/';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(so_no,3), '999')),0) AS no FROM stock_opname_headers WHERE so_no LIKE '$prefixTrxNumber%'");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }
        $trxNumber = $prefixTrxNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        /** Check already submitted stock opname or not */
        $countPendingSo = StockOpnameHeader::where('site_id', $site->id)->where('location_id', $location->id)
            ->where(function (Builder|QueryBuilder $query) use ($statusSoSubmit, $statusSoFreeze, $statusSoStockInput) {
                $query->where('flag', $statusSoSubmit)->orWhere('flag', $statusSoFreeze)->orWhere('flag', $statusSoStockInput);
            })->count();
        if ($countPendingSo > 0) {
            throw ValidationException::withMessages(['detail' => 'Failed to submit stock opname, there is still a process that has not been completed.']);
        }

        DB::beginTransaction();
        try {
            (int) $totalItem = 0;
            (int) $totalQty = 0;

            /** Insert stock opname header */
            $soHeader = StockOpnameHeader::create([
                'so_no' => $trxNumber,
                'so_date' => $soDate,
                'so_type' => $validated['type'],
                'site_id' => $site->id,
                'site_code' => $site->site_code,
                'location_id' => $location->id,
                'location_code' => $location->location_code,
                'total_items' => $totalItem,
                'total_qty' => $totalQty,
                'flag' => $statusSoSubmit,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            /** Looping details */
            foreach ($validated['detail'] as $detail) {
                $product = ProductCategory::where('id', $detail['product_id'])->first();
                $stock = Stock::where('site_id', $site->id)->where('catg_id', $product->id)->where('location_id', $location->id)->first();

                /** Check stock is freeze or not */
                if ($stock->so_flag == 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit stock opname, stock product '.$product->catg_name.' is used in another process.']);
                }

                /** Check product is active or not */
                if ($product->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit stock opname, product '.$product->catg_name.' is not active.']);
                }

                /** Check location is active or not */
                if ($location->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit stock opname, location '.$location->location_name.' is not active.']);
                }

                /** Generate stock opname detail */
                StockOpnameDetail::create([
                    'so_id' => $soHeader->id,
                    'catg_id' => $stock->catg_id,
                    'catg_code' => $stock->catg_code,
                    'catg_desc' => $product->catg_name,
                    'before_quantity' => $stock->quantity,
                    'unit' => $stock->unit,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                $totalItem++;
                $totalQty += $stock->quantity;
            }

            /** Update total qty in headers */
            $soHeader->total_items = $totalItem;
            $soHeader->total_qty = $totalQty;
            $soHeader->save();

            (string) $title = 'Success';
            (string) $message = 'Partial stock opname request successfully submitted with number: '.$trxNumber;
            (array) $data = [
                'trx_number' => $trxNumber,
            ];
            (string) $route = route('list-stock-opname');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit partial stock opname request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit partial stock opname request', 422, $e);
        }
    }

    /**
     * GET request for getting list of stock opname
     */
    public function getStockOpnameListDatatable(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'so_no' => ['nullable', 'string'],
            'site' => ['nullable', 'integer'],
            'location' => ['nullable', 'string'],
            'status' => ['nullable', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusSoSubmit = Status::where('module', 'stock_opname')->where('flag_value', 0)->first()->flag_value;
        $statusSoFreeze = Status::where('module', 'stock_opname')->where('flag_value', 1)->first()->flag_value;
        $statusSoStockInput = Status::where('module', 'stock_opname')->where('flag_value', 2)->first()->flag_value;
        $statusSoFinished = Status::where('module', 'stock_opname')->where('flag_value', 3)->first()->flag_value;

        /** Get sites permission */
        $userSites = UserSite::where('user_id', $user?->id)->get()->pluck('site_code')->toArray();

        /** Prepare for parameters */
        $params = '';
        if (! is_null($validated['from_date'])) {
            $params .= " AND sh.so_date >= '".Carbon::parse($validated['from_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($validated['to_date'])) {
            $params .= " AND sh.so_date <= '".Carbon::parse($validated['to_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($validated['so_no'])) {
            $params .= " AND sh.so_no ILIKE '%".$validated['so_no']."%'";
        }
        if (! is_null($validated['site'])) {
            $params .= " AND sh.site_id = ".$validated['site'];
        }
        if (! is_null($validated['location'])) {
            $params .= " AND sh.location_code ILIKE '%".$validated['location']."%'";
        }
        if (! is_null($validated['status'])) {
            $params .= " AND sh.flag = ".$validated['status'];
        }

        $sql = ("SELECT sh.id AS so_id, sh.so_no, sh.so_date, sh.site_code, s.store_code,
                l.location_code, l.location_name, UPPER(sh.so_type) AS so_type,
                sh.flag, sts.flag_desc AS status
            FROM stock_opname_headers sh, sites s, locations l, status sts
            WHERE sh.site_id = s.id
                AND sh.location_id = l.id
                AND sh.flag = sts.flag_value
                AND sts.module = 'stock_opname'
                AND EXISTS (
                    SELECT 1
                    FROM user_sites us
                    WHERE us.user_id = $user?->id
                        AND us.site_id = sh.site_id
                )$params
            ORDER BY sh.so_date DESC");
		$data = DB::select($sql);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("actions", function($row) use($statusSoSubmit, $statusSoFreeze, $statusSoStockInput, $statusSoFinished, $userSites) {
                $buttons = null;

                /** Freeze */
                if ($row->flag == $statusSoSubmit && in_array($row->site_code, $userSites)) {
                    $buttons .= '<a href="/freeze-stock-opname/'.$row->so_id.'" title="Freeze">
                        <button type="submit" class="btn" id="btn-approve-list">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-check" viewBox="0 0 16 16">
                                <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
                                <path d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-12Z"/>
                                <path d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3Z"/>
                            </svg>
                        </button>
                    </a>';
                }
                /** Stock Input */
                if (($row->flag == $statusSoFreeze || $row->flag == $statusSoStockInput) && in_array($row->site_code, $userSites)) {
                    $buttons .= '<a href="/input-stock-opname/'.$row->so_id.'" title="Stock Input">
                        <button type="submit" class="btn btn-primary editUser" onclick="window.dialog_edit.showModal();" data-u="{{ $user }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                            </svg>
                        </button>
                    </a>';
                }
                /** Process Data */
                if ($row->flag == $statusSoStockInput && in_array($row->site_code, $userSites)) {
                    $buttons .= '<button type="submit" class="btn btn-process-data" id="btn-approve-list" title="Process Data" onClick="processData('.$row->so_id.')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-check" viewBox="0 0 16 16">
                            <path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5h3Z"/>
                            <path d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-12Z"/>
                            <path d="M10.854 7.854a.5.5 0 0 0-.708-.708L7.5 9.793 6.354 8.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3Z"/>
                        </svg>
                    </button>';
                }
                /** View */
                if ($row->flag == $statusSoFinished && in_array($row->site_code, $userSites)) {
                    $buttons = '<a href="/view-stock-opname/'.$row->so_id.'" title="View">
                        <button type="submit" class="btn btn-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                        </button>
                    </a>';
                }

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * GET stock opname freeze page
     */
    public function freezeStockOpnamePage($id): View
    {
        $user = Auth::user();

        /** Get stock opname header data */
        $sqlHeader = "SELECT sh.id AS so_id, sh.so_no, sh.so_date, sh.site_code, s.store_code,
                l.location_code, l.location_name, UPPER(sh.so_type) AS so_type
            FROM stock_opname_headers sh, sites s, locations l
            WHERE sh.site_id = s.id
                AND sh.location_id = l.id
                AND sh.id = $id
            LIMIT 1";

        /** Get stock opname detail data */
        $sqlDetail = "SELECT sd.id AS detail_id, sd.so_id, sd.catg_code, sd.catg_desc, sd.unit
            FROM stock_opname_details sd
            WHERE so_id = $id
            ORDER BY catg_desc";

        (array) $data = [
            'so_id' => $id,
            'so_header_data' => collect(DB::select($sqlHeader))->first(),
            'so_detail_data' => DB::select($sqlDetail),
        ];

        return view('freeze-stock-opname', $data);
    }

    /**
     * POST request for freeze stock
     */
    public function postStockOpnameFreezeStock(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'header_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusSoSubmit = Status::where('module', 'stock_opname')->where('flag_value', 0)->first()->flag_value;
        $statusSoFreeze = Status::where('module', 'stock_opname')->where('flag_value', 1)->first()->flag_value;
        $statusExpPendingApprove = Status::where('module', 'expending')->where('flag_value', 1)->first()->flag_value;
        $statusTrfPendingApprove = Status::where('module', 'transfer')->where('flag_value', 1)->first()->flag_value;

        /** Get Stock opname data */
        $soData = StockOpnameHeader::where('id', $validated['header_id'])->first();
        if (is_null($soData)) {
            Log::warning('Stock opname request not found', ['userId' => $user->id, 'soId' => $validated['header_id']]);
            throw ValidationException::withMessages(['header_id' => 'Stock opname request not found']);
        }

        /** Check if status is submit */
        if ($soData->flag != $statusSoSubmit) {
            Log::warning('Stock opname request status is not submit', ['userId' => $user->id, 'soId' => $validated['header_id']]);
            throw ValidationException::withMessages(['header_id' => 'Stock opname request status is not submit']);
        }

        /** Check there is pending transaction or not */
        $dataExp = ExpendingHeader::where('origin_site_id', $soData->site_id)->where('location_id', $soData->location_id)->where('flag', $statusExpPendingApprove)->count();
        if ($dataExp > 0) {
            Log::warning('Failed, there is expending need approve', ['userId' => $user->id, 'soId' => $validated['header_id']]);
            throw ValidationException::withMessages(['header_id' => 'Failed, there is expending need approve']);
        }

        $sqlCheckTrf = "SELECT COUNT(*) AS hit
            FROM transfer_headers th, transfer_details td
            WHERE th.id = td.trf_id
                AND th.origin_site_id = $soData->site_id
                AND td.from_location_id = $soData->location_id
                AND th.flag = $statusTrfPendingApprove";
        $dataTrf = collect(DB::select($sqlCheckTrf))->first()->hit;
        if ($dataTrf > 0) {
            Log::warning('Failed, there is transfer need approve', ['userId' => $user->id, 'soId' => $validated['header_id']]);
            throw ValidationException::withMessages(['header_id' => 'Failed, there is transfer need approve']);
        }

        DB::beginTransaction();
        try {
            /** Get detail */
            $soDetailData = StockOpnameDetail::where('so_id', $soData->id)->get();
            foreach ($soDetailData as $detail) {
                /** Get stock data */
                $stock = Stock::where('site_id', $soData->site_id)->where('catg_id', $detail->catg_id)->where('location_id', $soData->location_id)->first();

                /** Validate so flag */
                if ($stock->so_flag == 1) {
                    Log::warning('Stock '.$detail['catg_desc'].' is freeze in another stock opname process', ['userId' => $user->id, 'soId' => $validated['header_id']]);
                    throw ValidationException::withMessages(['detail' => 'Stock '.$detail['catg_desc'].' is freeze in another stock opname process']);
                }

                /** Change so flag */
                $stock->so_flag = 1;
                $stock->updated_by = $user?->id;
                $stock->updated_at = date('Y-m-d H:i:s');
                $stock->save();

                /** Update before quantity in so detail */
                $detail->before_quantity = $stock->quantity;
                $detail->save();
            }

            /** Update header */
            $soData->flag = $statusSoFreeze;
            $soData->updated_by = $user?->id;
            $soData->updated_at = date('Y-m-d H:i:s');
            $soData->save();

            (string) $title = 'Success';
            (string) $message = 'Stock successfully freezed';

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => null,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when freeze stock', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to freeze stock', 422, $e);
        }
    }

    /**
     * POST request for cancel stock opname request
     */
    public function postStockOpnameCancel(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'header_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusSoSubmit = Status::where('module', 'stock_opname')->where('flag_value', 0)->first()->flag_value;
        $statusSoCancel = Status::where('module', 'stock_opname')->where('flag_value', 9)->first()->flag_value;

        /** Get data */
        $soData = StockOpnameHeader::where('id', $validated['header_id'])->first();
        if (is_null($soData)) {
            Log::warning('Stock opname request not found', ['userId' => $user->id, 'soId' => $validated['header_id']]);
            throw ValidationException::withMessages(['header_id' => 'Stock opname request not found']);
        }

        /** Check if status is submit */
        if ($soData->flag != $statusSoSubmit) {
            Log::warning('Stock opname request status is not submit', ['userId' => $user->id, 'soId' => $validated['header_id']]);
            throw ValidationException::withMessages(['header_id' => 'Stock opname request status is not submit']);
        }

        DB::beginTransaction();
        try {
            /** Update header */
            $soData->flag = $statusSoCancel;
            $soData->updated_by = $user?->id;
            $soData->updated_at = date('Y-m-d H:i:s');
            $soData->save();

            (string) $title = 'Success';
            (string) $message = 'Stock opname request successfully canceled';
            (string) $route = route('list-stock-opname');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when cancel stock opname request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to cancel stock opname request', 422, $e);
        }
    }

    /**
     * GET stock opname input stock page
     */
    public function inputStockOpnamePage($id): View
    {
        $user = Auth::user();

        /** Get stock opname header data */
        $sqlHeader = "SELECT sh.id AS so_id, sh.so_no, sh.so_date, sh.site_code, s.store_code,
                l.location_code, l.location_name, UPPER(sh.so_type) AS so_type
            FROM stock_opname_headers sh, sites s, locations l
            WHERE sh.site_id = s.id
                AND sh.location_id = l.id
                AND sh.id = $id
            LIMIT 1";

        /** Get stock opname detail data */
        $sqlDetail = "SELECT sd.id AS detail_id, sd.so_id, sd.catg_code, sd.catg_desc, sd.unit, sd.after_quantity
            FROM stock_opname_details sd
            WHERE so_id = $id
            ORDER BY catg_desc";

        (array) $data = [
            'so_id' => $id,
            'so_header_data' => collect(DB::select($sqlHeader))->first(),
            'so_detail_data' => DB::select($sqlDetail),
        ];

        return view('input-stock-opname', $data);
    }

    /**
     * POST request for submitting stock opname input stock
     */
    public function postStockOpnameInputStock(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'header_id' => ['required', 'integer'],
            'detail' => ['required', 'array'],
            'detail.*.detail_id' => ['required', 'integer'],
            'detail.*.qty' => ['nullable', 'integer', 'min:0'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status flag value */
        $statusSoFreeze = Status::where('module', 'stock_opname')->where('flag_value', 1)->first()->flag_value;
        $statusSoStockInput = Status::where('module', 'stock_opname')->where('flag_value', 2)->first()->flag_value;

        /** Get data */
        $soData = StockOpnameHeader::where('id', $validated['header_id'])->first();
        if (is_null($soData)) {
            Log::warning('Stock opname request not found', ['userId' => $user->id, 'soId' => $validated['header_id']]);
            throw ValidationException::withMessages(['header_id' => 'Stock opname request not found']);
        }

        /** Check if status is freeze or stock input */
        if ($soData->flag != $statusSoFreeze && $soData->flag != $statusSoStockInput) {
            Log::warning('Stock opname request status is not freeze or stock input', ['userId' => $user->id, 'soId' => $validated['header_id']]);
            throw ValidationException::withMessages(['header_id' => 'Stock opname request status is not freeze or stock input']);
        }

        DB::beginTransaction();
        try {
            /** Looping details */
            foreach ($validated['detail'] as $detail) {
                $soDetailData = StockOpnameDetail::where('id', $detail['detail_id'])->where('so_id', $soData->id)->first();

                if (is_null($soDetailData)) {
                    Log::warning('Stock opname detail not found', ['userId' => $user->id, 'soId' => $validated['header_id'], 'soDetailId' => $detail['detail_id']]);
                    throw ValidationException::withMessages(['detail_id' => 'Stock opname detail not found']);
                }

                /** Get stock data */
                $stock = Stock::where('site_id', $soData->site_id)->where('catg_id', $soDetailData->catg_id)->where('location_id', $soData->location_id)->first();

                /** Validate so flag */
                if ($stock->so_flag != 1) {
                    Log::warning('Stock '.$detail['catg_desc'].' is not freeze', ['userId' => $user->id, 'soId' => $validated['header_id'], 'soDetailId' => $detail['detail_id'], 'stockId' => $stock->id]);
                    throw ValidationException::withMessages(['detail' => 'Stock '.$detail['catg_desc'].' is not freeze']);
                }

                $soDetailData->after_quantity = $detail['qty'];
                $soDetailData->updated_by = $user?->id;
                $soDetailData->updated_at = date('Y-m-d H:i:s');
                $soDetailData->save();
            }

            /** Update header */
            $soData->flag = $statusSoStockInput;
            $soData->updated_by = $user?->id;
            $soData->updated_at = date('Y-m-d H:i:s');
            $soData->save();

            (string) $title = 'Success';
            (string) $message = 'Stock input successfully submitted.';
            (string) $route = route('list-stock-opname');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit stock input', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit stock input', 422, $e);
        }
    }

    /**
     * POST request for submitting stock opname process data
     */
    public function postStockOpnameProcessData(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'header_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status flag value */
        $statusSoStockInput = Status::where('module', 'stock_opname')->where('flag_value', 2)->first()->flag_value;
        $statusSoFinished = Status::where('module', 'stock_opname')->where('flag_value', 3)->first()->flag_value;

        /** Get movement type */
        $movCodeSo = MovementType::where('mov_code', InterfaceClass::STOCKOPNAME_MOVEMENT)->first()?->mov_code;

        /** Get data */
        $soData = StockOpnameHeader::where('id', $validated['header_id'])->first();
        if (is_null($soData)) {
            Log::warning('Stock opname request not found', ['userId' => $user->id, 'soId' => $validated['header_id']]);
            throw ValidationException::withMessages(['header_id' => 'Stock opname request not found']);
        }

        /** Check if status is freeze or stock input */
        if ($soData->flag != $statusSoStockInput) {
            Log::warning('Stock opname request status is not stock input', ['userId' => $user->id, 'soId' => $validated['header_id']]);
            throw ValidationException::withMessages(['header_id' => 'Stock opname request status is not stock input']);
        }

        DB::beginTransaction();
        try {
            /** If type is partial SO, delete details that column after_quantity is null */
            if ($soData->so_type == 'partial') {
                // StockOpnameDetail::where('so_id', $soData->id)->whereNull('after_quantity')->delete();
                $soDetailList = StockOpnameDetail::where('so_id', $soData->id)->whereNull('after_quantity')->get();

                foreach ($soDetailList as $detail) {
                    /** Get stock opname detail data */
                    $detailData = StockOpnameDetail::where('id', $detail['id'])->where('so_id', $soData->id)->first();

                    /** Get stock data */
                    $stock = Stock::where('site_id', $soData->site_id)->where('catg_id', $detailData->catg_id)->where('location_id', $soData->location_id)->first();
                    if ($stock->so_flag != 1) {
                        Log::warning('Stock '.$detail['catg_desc'].' is not freeze', ['userId' => $user->id, 'soId' => $validated['header_id'], 'soDetailId' => $detail['id'], 'stockId' => $stock->id]);
                        throw ValidationException::withMessages(['detail' => 'Stock '.$detail['catg_desc'].' is not freeze']);
                    }

                    /** Update stock */
                    $stock->so_flag = 0;
                    $stock->updated_by = $user?->id;
                    $stock->updated_at = date('Y-m-d H:i:s');
                    $stock->save();

                    /** Delete detail */
                    $detail->delete();
                }
            }

            /** Looping details */
            $soDetailList = StockOpnameDetail::where('so_id', $soData->id)->get();
            foreach ($soDetailList as $detail) {
                /** Get stock opname detail data */
                $detailData = StockOpnameDetail::where('id', $detail['id'])->where('so_id', $soData->id)->first();

                /** Get stock data */
                $stock = Stock::where('site_id', $soData->site_id)->where('catg_id', $detailData->catg_id)->where('location_id', $soData->location_id)->first();
                if ($stock->so_flag != 1) {
                    Log::warning('Stock '.$detail['catg_desc'].' is not freeze', ['userId' => $user->id, 'soId' => $validated['header_id'], 'soDetailId' => $detail['id'], 'stockId' => $stock->id]);
                    throw ValidationException::withMessages(['detail' => 'Stock '.$detail['catg_desc'].' is not freeze']);
                }

                /** Update stock opname detail */
                $detailData->after_quantity = (is_null($detailData->after_quantity) ? 0 : $detailData->after_quantity);
                $detailData->variance_qty = $detailData->after_quantity - $detailData->before_quantity;
                $detailData->updated_by = $user?->id;
                $detailData->updated_at = date('Y-m-d H:i:s');
                $detailData->save();

                /** Update stock */
                $stock->quantity = $detailData->after_quantity;
                $stock->so_flag = 0;
                $stock->updated_by = $user?->id;
                $stock->updated_at = date('Y-m-d H:i:s');
                $stock->save();

                /** Generate stock movement */
                StockMovement::create([
                    'mov_date' => date('Y-m-d'),
                    'site_id' => $soData->site_id,
                    'site_code' => $soData->site_code,
                    'location_id' => $soData->location_id,
                    'location_code' => $soData->location_code,
                    'catg_id' => $detailData->catg_id,
                    'catg_code' => $detailData->catg_code,
                    'quantity' => $detailData->variance_qty,
                    'unit' => $detailData->unit,
                    'mov_code' => $movCodeSo,
                    'purch_price' => 0,
                    'sales_price' => 0,
                    'ref_no' => $soData->so_no,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);
            }

            /** Update header */
            $soData->flag = $statusSoFinished;
            $soData->updated_by = $user?->id;
            $soData->updated_at = date('Y-m-d H:i:s');
            $soData->save();

            (string) $title = 'Success';
            (string) $message = 'Stock opname successfully processed.';

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => null,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when process stock opname data', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to process stock opname data', 422, $e);
        }
    }

    /**
     * GET stock opname view page
     */
    public function viewStockOpnamePage($id): View
    {
        $user = Auth::user();

        /** Get stock opname header data */
        $sqlHeader = "SELECT sh.id AS so_id, sh.so_no, sh.so_date, sh.site_code, s.store_code, s.site_description,
                l.location_code, l.location_name, UPPER(sh.so_type) AS so_type
            FROM stock_opname_headers sh, sites s, locations l
            WHERE sh.site_id = s.id
                AND sh.location_id = l.id
                AND sh.id = $id
            LIMIT 1";

        /** Get stock opname detail data */
        $sqlDetail = "SELECT sd.id AS detail_id, sd.so_id, sd.catg_code, sd.catg_desc, sd.unit, sd.before_quantity, sd.after_quantity, sd.variance_qty
            FROM stock_opname_details sd
            WHERE so_id = $id
            ORDER BY catg_desc";

        (array) $data = [
            'so_id' => $id,
            'so_header_data' => collect(DB::select($sqlHeader))->first(),
            'so_detail_data' => DB::select($sqlDetail),
        ];

        return view('view-stock-opname', $data);
    }

    /**
     * GET request for stock opname document page
     */
    public function documentStockOpnamePage($id): View
    {
        $user = Auth::user();

        /** Get stock opname header data */
        $sqlHeader = "SELECT sh.id AS so_id, sh.so_no, sh.so_date, sh.site_code, s.store_code,
                l.location_code, l.location_name, UPPER(sh.so_type) AS so_type
            FROM stock_opname_headers sh, sites s, locations l
            WHERE sh.site_id = s.id
                AND sh.location_id = l.id
                AND sh.id = $id
            LIMIT 1";

        /** Get stock opname detail data */
        $sqlDetail = "SELECT sd.id AS detail_id, sd.so_id, sd.catg_code, sd.catg_desc, sd.unit, sd.before_quantity, sd.after_quantity, sd.variance_qty
            FROM stock_opname_details sd
            WHERE so_id = $id
            ORDER BY catg_desc";

        (array) $data = [
            'so_id' => $id,
            'so_header_data' => collect(DB::select($sqlHeader))->first(),
            'so_detail_data' => DB::select($sqlDetail),
        ];

        return view('document-stock-opname', $data);
    }


    public function exportExcelListStockOpname(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'so_no' => ['nullable', 'string'],
            'site' => ['nullable', 'integer'],
            'location' => ['nullable', 'string'],
            'status' => ['nullable', 'integer'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }

        return Excel::download(new StockOpnameExport($request), 'list_stock_opname.xlsx');
    }
}
