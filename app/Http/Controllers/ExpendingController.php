<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Exports\ExpendingExport;
use App\Interfaces\InterfaceClass;
use App\Models\ExpendingDetail;
use App\Models\ExpendingHeader;
use App\Models\Location;
use App\Models\Profile;
use App\Models\ProductCategory;
use App\Models\ProfileLocation;
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
use Illuminate\Support\Facades\Vite;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ExpendingController extends Controller
{
    public function formExpendingPage(Request $request): View
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::EXPENDING_CREATE)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();
        Log::debug('User accessed form expending page', ['userId' => $user?->id, 'userName' => $user?->name, 'remoteIp' => $request->ip()]);

        return view('form-expending');
    }

    public function getExpProductList(Request $request){
        $user = Auth::user();

        $validate = Validator::make($request->all(), [
            'site_id' => ['required', 'integer'],
            'location_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()){
            throw new ValidationException($validate);
        }

        (array) $validated = $validate->validated();

        $siteId = $validated['site_id'];
        $locationId = $validated['location_id'];

        $sql = ("SELECT DISTINCT pc.id AS product_id, pc.catg_code, pc.catg_name
            FROM stocks s, product_categories pc
            WHERE s.catg_id = pc.id
                AND s.site_id = $siteId
                AND s.location_id = $locationId
                AND pc.flag = 1
            ORDER BY pc.catg_name");
        $data = DB::select($sql);

        return response()->json($data);
    }


    public function getExpProductLocationList(Request $request){
        $user = Auth::user();

        $validate = Validator::make($request->all(), [
            'site_id' => ['required', 'integer'],
            // 'product_id' => ['required', 'integer'],
        ]);

        if($validate->fails()){
            throw new ValidationException($validate);
        }

        (array) $validated = $validate->validated();
        $siteId = $validated['site_id'];
        // $productId = $validated['product_id'];

        $sql = ("SELECT DISTINCT l.id AS location_id, l.location_code, l.location_name FROM stocks s, locations l
            WHERE s.location_id = l.id
                AND s.site_id = $siteId
                -- AND catg_id
                AND l.flag = 1
            ORDER BY l.location_name");
        $data = DB::select($sql);

        return response()->json($data);
    }


    public function getExpStockQty(Request $request){
        $user = Auth::user();

        $validate = Validator::make($request->all(), [
            'site_id' => ['required','integer'],
            'product_id' => ['required','integer'],
            'location_id' => ['required','integer'],
        ]);
        if ($validate->fails()){
            throw new ValidationException($validate);
        }

        (array) $validated = $validate->validated();
        $siteId = $validated['site_id'];
        $productId = $validated['product_id'];
        $locationId = $validated['location_id'];

        $dataStock = Stock::where('site_id', $siteId)->where('catg_id', $productId)->where('location_id', $locationId)->first();

        $dataStockBooking = StockBooking::where('site_id', $siteId)->where('catg_id', $productId)->where('location_id', $locationId)->sum('quantity');
        (array) $data = [
            'data_stock' => $dataStock,
            'data_stock_booking' => $dataStockBooking,
        ];

        return response()->json($data);
    }

    public function getExpReqSubmit(Request $request){

        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::EXPENDING_CREATE)) {
            return abort(403, 'Insufficient permission.');
        }
        $user = Auth::user();

        $validate = Validator::make($request->all(), [
            'expending_date' => ['required'],
            'site_id' => ['required', 'integer'],
            'location_id' => ['required', 'integer'],
            'note' => ['required', 'string'],
            'detail' => ['required', 'array'],
            'detail.*.product_id' => ['required', 'integer'],
            'detail.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        if ($validate->fails()){
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $uppercaseNote = strtoupper($validated['note']);

        // return response()->json($uppercaseNote);

        // Get flag status
        $statusExpPendingApprove = Status::where('module', 'expending')->where('flag_value', 1)->first()->flag_value;

        // Get Sites
        $site = Site::where('id', $validated['site_id'])->first();
        $location = Location::where('id', $validated['location_id'])->first();

        /** Prepare expending number, Format: EXP/MM.YY/STORE_CODE/SEQ */
        $expendingDate = Carbon::createFromFormat('d/m/Y', $validated['expending_date'], 'Asia/Jakarta')->setTimezone('Asia/Jakarta');
        $expDateMonthYear = Carbon::parse($expendingDate)->setTimezone('Asia/Jakarta')->format('m.y');
        $prefixExpNumber = 'EXP/'.$expDateMonthYear.'/'.$site->store_code.'/';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(req_no,3), '999')),0) AS no FROM expending_headers WHERE req_no LIKE '$prefixExpNumber%'");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }
        $expNumber = $prefixExpNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            // Insert expending headers
            $expHeader = ExpendingHeader::create([
                'req_no' => $expNumber,
                'req_date' => $expendingDate,
                'origin_site_id' => $site->id,
                'origin_site_code' => $site->site_code,
                'location_id' => $location->id,
                'location_code' => $location->location_code,
                'flag' => $statusExpPendingApprove,
                'created_by' => $user?->id,
                'updated_by' =>$user?->id,
                'note' =>$uppercaseNote,
            ]);

            // Insert looping expending details
            foreach ($validated['detail'] as $detail){
                $productCategory = ProductCategory::where('id', $detail['product_id'])->first();

                $stock = Stock::where('site_id', $site->id)->where('catg_id', $productCategory->id)->where('location_id', $location->id)->first();
                $stockBookingSum = StockBooking::where('site_id', $site->id)->where('catg_id', $productCategory->id)->where('location_id', $location->id)->sum('quantity');

                /** Check stock is freeze or not */
                if ($stock->so_flag == 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit expending, stock product '.$productCategory->catg_name.' is freeze.']);
                }

                /** Check product is active or not */
                if ($productCategory->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit expending, product '.$productCategory->catg_name.' is not active.']);
                }

                /** Check location is active or not */
                if ($location->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit expending, location '.$location->location_name.' is not active.']);
                }

                /** Validate input qty with stock & booking qty */
                if (($stock->quantity - $stockBookingSum) < $detail['qty']) {
                    Log::warning('Stock '.$productCategory->catg_name.' is not available. Total Expending Qty: '.$detail['qty'].', Stock Qty: '.$stock->quantity.', Stock Booking Qty: '.$stockBookingSum, ['userId' => $user->id, 'expId' => $validated['exp_header_id']]);
                    throw ValidationException::withMessages(['detail' => 'Stock '.$productCategory->catg_name.' is not available. Total Expending Qty: '.$detail['qty'].', Stock Qty: '.$stock->quantity.', Stock Booking Qty: '.$stockBookingSum]);
                }

                /** Insert expending detail */
                ExpendingDetail::create([
                    'req_id' => $expHeader->id,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'catg_desc' => $productCategory->catg_name,
                    'unit_price' => 0,
                    'req_quantity' => $detail['qty'],
                    'unit' => $productCategory->unit,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

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
                    'book_type' => 'EXP', /** Expending */
                    'reference_no' => $expNumber,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);
            }

            (string) $title = 'Success';
            (string) $message = 'Expending request successfully submitted with number: '.$expNumber;
            (array) $data = [
                'exp_number' => $expNumber,
            ];
            (string) $route = route('list-expending');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit expending request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit expending request', 422, $e);
        }
    }

     /**
     * POST request for approve expending request
     */
    public function postExpReqApprove(Request $request)
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::EXPENDING_APPROVAL)) {
            return abort(403, 'Insufficient permission.');
        }

        // return response()->json($request);
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'exp_header_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusExpPendingApprove = Status::where('module', 'expending')->where('flag_value', 1)->first()->flag_value;
        $statusExpApprove = Status::where('module', 'expending')->where('flag_value', 2)->first()->flag_value;

        /** Get expending data */
        $expData = ExpendingHeader::where('id', $validated['exp_header_id'])->first();

        if (is_null($expData)) {
            Log::warning('Expending request not found', ['userId' => $user->id, 'expId' => $validated['exp_header_id']]);
            throw ValidationException::withMessages(['exp_header_id' => 'Expending request not found']);
        }

        /** Check if status is pending approval */
        if ($expData->flag != $statusExpPendingApprove) {
            Log::warning('Expending request status is not pending approve', ['userId' => $user->id, 'expId' => $validated['exp_header_id']]);
            throw ValidationException::withMessages(['exp_header_id' => 'Expending request status is not pending approve']);
        }

        DB::beginTransaction();
        try {
            /** Get expending detail */
            $expDetailData = ExpendingDetail::where('req_id', $expData->id)->get();
            foreach ($expDetailData as $detail) {
                /** Get stock data */
                $stock = Stock::where('site_id', $expData->origin_site_id)->where('catg_id', $detail->catg_id)->where('location_id', $expData->location_id)->first();
                $productCategory = ProductCategory::where('id', $detail->catg_id)->first();

                /** Check stock is freeze or not */
                if ($stock->so_flag == 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to approve expending, stock product '.$productCategory->catg_name.' is freeze.']);
                }

                /** Validate stock quantity */
                if ($stock->quantity < $detail->req_quantity) {
                    Log::warning('Stock is not available. Total Expending Qty: '.$detail->req_quantity.', Stock Qty: '.$stock->quantity, ['userId' => $user->id, 'expId' => $validated['exp_header_id']]);
                    throw ValidationException::withMessages(['detail' => 'Stock is not available. Total Expending Qty: '.$detail->req_quantity.', Stock Qty: '.$stock->quantity]);
                }
                $stock->quantity -= $detail->req_quantity;
                $stock->save();

                // return response()->json($expData->origin_site_id);

                /** Generate stock movement */
                StockMovement::create([
                    'mov_date' => date('Y-m-d'),
                    'site_id' => $expData->origin_site_id,
                    'site_code' => $expData->origin_site_code,
                    'location_id' => $expData->location_id,
                    'location_code' => $expData->location_code,
                    'catg_id' => $detail->catg_id,
                    'catg_code' => $detail->catg_code,
                    'quantity' => $detail->req_quantity * -1, /** Convert to negative amount because expending out */
                    'unit' => $detail->unit,
                    'mov_code' => 'EXP', /** Reference with table movement_types */
                    'purch_price' => 0,
                    'sales_price' => 0,
                    'ref_no' => $expData->req_no,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);
            }
            /** Remove booking qty */
            StockBooking::where('book_type', 'EXP')->where('reference_no', $expData->req_no)->delete();

            /** Update header */
            $expData->flag = $statusExpApprove;
            $expData->approved_by = $user?->id;
            $expData->approved_date = date('Y-m-d H:i:s');
            $expData->updated_by = $user?->id;
            $expData->save();

            (string) $title = 'Success';
            (string) $message = 'Expending request successfully approved';
            (string) $route = route('list-expending');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when approve expending request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException($e->getMessage(), 422, $e);
        }
    }

    public function postExpReqReject(Request $request){

        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::EXPENDING_APPROVAL)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'exp_header_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusExpPendingApprove = Status::where('module', 'expending')->where('flag_value', 1)->first()->flag_value;
        $statusExpReject = Status::where('module', 'expending')->where('flag_value', 8)->first()->flag_value;

        /** Get data */
        $expData = ExpendingHeader::where('id', $validated['exp_header_id'])->first();
        if (is_null($expData)) {
            Log::warning('Expending request not found', ['userId' => $user->id, 'expId' => $validated['exp_header_id']]);
            throw ValidationException::withMessages(['exp_header_id' => 'Expending request not found']);
        }

        /** Check if status is pending approval */
        if ($expData->flag != $statusExpPendingApprove) {
            Log::warning('Expending request status is not pending approve', ['userId' => $user->id, 'expId' => $validated['exp_header_id']]);
            throw ValidationException::withMessages(['exp_header_id' => 'Expending request status is not pending approve']);
        }

        DB::beginTransaction();
        try {
            /** Update header */
            $expData->flag = $statusExpReject;
            $expData->updated_by = $user?->id;
            $expData->updated_at = date('Y-m-d H:i:s');
            $expData->save();

            /** Remove booking qty */
            StockBooking::where('book_type', 'EXP')->where('reference_no', $expData->req_no)->delete();

            (string) $title = 'Success';
            (string) $message = 'Expending request successfully rejected';
            (string) $route = route('list-expending');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when reject expending request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException($e->getMessage(), 422, $e);
        }

    }

    public function listExpendingPage(Request $request): View
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::EXPENDING_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();
        Log::debug('User is requesting expending list page', ['userId' => $user?->id, 'userName' => $user?->name, 'remoteIp' => $request->ip()]);

        return view('list-expending');
    }

    public function getExpListDatatable(Request $request){
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::EXPENDING_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();
        Log::debug('User is requesting get expending list', ['userId' => $user?->id, 'userName' => $user?->name, 'remoteIp' => $request->ip()]);

        /** Get status id */
        $statusExpPendingApprove = Status::where('module', 'expending')->where('flag_value', 1)->first()->flag_value;

        /** Get sites permission */
        $userSites = UserSite::where('user_id', $user?->id)->get()->pluck('site_code')->toArray();

         /** Validate Input */
         $validate = Validator::make($request->all(), [
            'exp_no' => ['nullable', 'string'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'site' => ['nullable', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $profileLocation = ProfileLocation::where('profile_id', $user?->profile_id)->count();

        /** Prepare for parameters */
        $params = '';
        if (! is_null($validated['exp_no'])) {
            $params .= " AND eh.req_no ILIKE '%".$validated['exp_no']."%'";
        }
        if (! is_null($validated['site'])) {
            $params .= " AND sites.site_code = ".$validated['site'];
        }
        if (! is_null($validated['from_date'])) {
            $params .= " AND eh.req_date >= '".Carbon::parse($validated['from_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($validated['to_date'])) {
            $params .= " AND eh.req_date <= '".Carbon::parse($validated['to_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if ($profileLocation > 0) {
            $params .= " AND EXISTS (SELECT 1 FROM profile_locations pl WHERE eh.location_id = pl.location_id AND pl.profile_id = $user->profile_id)";
        }

        $sql = ("SELECT eh.id AS exp_id, eh.req_no, eh.req_date, sites.site_code, sites.store_code, s.flag_desc AS status, s.flag_value
            FROM expending_headers eh, sites, status s
            WHERE eh.origin_site_id = sites.id
                AND eh.flag = s.flag_value
                AND s.module = 'expending'
                AND EXISTS (
                    SELECT 1
                    FROM user_sites us
                    WHERE us.site_id = sites.id
                        AND us.user_id = $user->id
                )$params
            ORDER BY eh.req_date DESC");
            $data = DB::select($sql);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("actions", function($row) use($statusExpPendingApprove, $userSites) {
                $buttons = '<a href="/view-expending/'.$row->exp_id.'" title="View">
                    <button type="submit" class="btn btn-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                    </button>
                </a>';

                if ($row->flag_value == $statusExpPendingApprove && Profile::authorize(InterfaceClass::EXPENDING_APPROVAL) && in_array($row->site_code, $userSites)) {
                    $buttons .= '<a href="/approve-expending/'.$row->exp_id.'" title="Approve">
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

    public function viewExpendingPage($id): View
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::EXPENDING_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();
        Log::debug('User is request expending request page', ['userId' => $user?->id, 'userName' => $user?->name, 'expId' => $id]);

        // Get expending header data
        $sqlHeader = "SELECT eh.id, eh.req_no, eh.req_date, eh.location_id, eh.location_code, l.location_name, sites.site_code, sites.store_code, sites.site_description, eh.note
            FROM expending_headers eh, sites, locations l
            WHERE eh.origin_site_id = sites.id
                AND eh.id = $id
                AND eh.location_id = l.id
            LIMIT 1";

        $sqlDetail = "SELECT ed.id, ed.catg_id, ed.catg_code, ed.catg_desc, ed.req_quantity, ed.unit
        FROM expending_details ed
        WHERE ed.req_id = $id
        ORDER BY ed.catg_desc";

        (array) $data = [
            'exp_id' => $id,
            'exp_header_data' => collect(DB::select($sqlHeader))->first(),
            'exp_detail_data' => DB::select($sqlDetail),
        ];
        return view('view-expending', $data);
    }

    public function approveExpendingPage($id): View{
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::EXPENDING_APPROVAL)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();
        Log::debug('User is request expending request approval page', ['userId' => $user?->id, 'userName' => $user?->name, 'expId' => $id]);

        // Get expending header data
        $sqlHeader = "SELECT eh.id, eh.req_no, eh.req_date, eh.location_id, eh.location_code, l.location_name, sites.site_code, sites.store_code, sites.site_description, eh.note
            FROM expending_headers eh, sites, locations l
            WHERE eh.origin_site_id = sites.id
                AND eh.id = $id
                AND eh.location_id = l.id
            LIMIT 1";

        $sqlDetail = "SELECT ed.id, ed.catg_id, ed.catg_code, ed.catg_desc, ed.req_quantity, ed.unit
        FROM expending_details ed
        WHERE ed.req_id = $id
        ORDER BY ed.catg_desc";

        (array) $data = [
            'exp_id' => $id,
            'exp_header_data' => collect(DB::select($sqlHeader))->first(),
            'exp_detail_data' => DB::select($sqlDetail),
        ];

        return view('approve-expending', $data);
    }

    public function exportExcelListExpending(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'expNumber' => ['nullable', 'string'],
            'site' => ['nullable', 'integer'],
            'dateFromData' => ['nullable', 'string'],
            'dateToData' => ['nullable', 'string'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }

        return Excel::download(new ExpendingExport($request), 'list_expending.xlsx');
    }
}


