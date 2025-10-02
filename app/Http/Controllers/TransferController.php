<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Exports\TransferExport;
use App\Interfaces\InterfaceClass;
use App\Models\Location;
use App\Models\MovementType;
use App\Models\ProductCategory;
use App\Models\Profile;
use App\Models\Site;
use App\Models\Status;
use App\Models\Stock;
use App\Models\StockBooking;
use App\Models\StockMovement;
use App\Models\TransferDetail;
use App\Models\TransferHeader;
use App\Models\UserSite;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class TransferController extends Controller
{
    /**
     * GET form transfer page
     */
    public function formTransferPage(Request $request): View
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::TRANSFER_CREATE)) {
            return abort(403, 'Insufficient permission.');
        }
        $user = Auth::user();
        Log::debug('User accessed form transfer page', ['userId' => $user?->id, 'userName' => $user?->name, 'remoteIp' => $request->ip()]);

        return view('form-transfer');
    }

    /**
     * GET request for getting transfer site to list
     */
    public function getTrfSiteToList(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'from_site_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $fromSiteId = $validated['from_site_id'];

        $sql = ("SELECT s.id AS site_id, s.site_code, s.store_code, s.site_description
            FROM sites s
            WHERE s.flag = 1
                AND s.id <> $fromSiteId
                AND s.site_type = 'CAB'
            ORDER BY s.site_code");
		$data = DB::select($sql);

        return response()->json($data);
    }

    /**
     * GET request for getting transfer product list
     */
    public function getTrfProductList(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'from_site_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $fromSiteId = $validated['from_site_id'];

        $sql = ("SELECT DISTINCT pc.id AS product_id, pc.catg_code, pc.catg_name, pc.unit
            FROM stocks s, product_categories pc
            WHERE s.catg_id = pc.id
                AND s.site_id = $fromSiteId
                AND pc.flag = 1
            ORDER BY pc.catg_name");
		$data = DB::select($sql);

        return response()->json($data);
    }

    /**
     * GET request for getting transfer product location list
     */
    public function getTrfProductLocationList(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'from_site_id' => ['required', 'integer'],
            'product_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $fromSiteId = $validated['from_site_id'];
        $productId = $validated['product_id'];

        $sql = ("SELECT l.id AS location_id, l.location_code, l.location_name
            FROM stocks s, locations l
            WHERE s.location_id = l.id
                AND s.site_id = $fromSiteId
                AND catg_id = $productId
                AND l.flag = 1
            ORDER BY l.location_name");
		$data = DB::select($sql);

        return response()->json($data);
    }

    /**
     * GET request for getting stock qty for transfer request
     */
    public function getTrfStockQty(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'from_site_id' => ['required', 'integer'],
            'product_id' => ['required', 'integer'],
            'location_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $fromSiteId = $validated['from_site_id'];
        $productId = $validated['product_id'];
        $locationId = $validated['location_id'];

        $dataStock = Stock::where('site_id', $fromSiteId)->where('catg_id', $productId)->where('location_id', $locationId)->first();
        $dataStockBooking = StockBooking::where('site_id', $fromSiteId)->where('catg_id', $productId)->where('location_id', $locationId)->sum('quantity');
        (array) $data = [
            'data_stock' => $dataStock,
            'data_stock_booking' => $dataStockBooking,
        ];

        return response()->json($data);
    }

    /**
     * POST request for submitting transfer request
     */
    public function postTrfReqSubmit(Request $request)
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::TRANSFER_CREATE)) {
            return abort(403, 'Insufficient permission.');
        }
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            // 'transfer_date' => ['required', 'date_format:d/m/Y'],
            'transfer_date' => ['required'],
            'from_site_id' => ['required', 'integer'],
            'to_site_id' => ['required', 'integer'],
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
        $statusTrfPendingApprove = Status::where('module', 'transfer')->where('flag_value', 1)->first()->flag_value;

        /** Get sites */
        $fromSite = Site::where('id', $validated['from_site_id'])->first();
        $toSite = Site::where('id', $validated['to_site_id'])->first();

        /** Prepare transaction number, Format: TRF/MM.YY/STORE_CODE/SEQ */
        $transferDate = Carbon::createFromFormat('d/m/Y', $validated['transfer_date'], 'Asia/Jakarta')->setTimezone('Asia/Jakarta');
        $trfDateMonthYear = Carbon::parse($transferDate)->setTimezone('Asia/Jakarta')->format('m.y');
        $prefixTrxNumber = 'TRF/'.$trfDateMonthYear.'/'.$fromSite->store_code.'/';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(trf_no,3), '999')),0) AS no FROM transfer_headers WHERE trf_no LIKE '$prefixTrxNumber%'");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }
        $trxNumber = $prefixTrxNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            /** Insert transfer header */
            $trfHeader = TransferHeader::create([
                'trf_no' => $trxNumber,
                'trf_date' => $transferDate,
                'origin_site_id' => $fromSite->id,
                'origin_site_code' => $fromSite->site_code,
                'destination_site_id' => $toSite->id,
                'destination_site_code' => $toSite->site_code,
                'flag' => $statusTrfPendingApprove,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            /** Looping details */
            foreach ($validated['detail'] as $detail) {
                $productCategory = ProductCategory::where('id', $detail['product_id'])->first();
                $location = Location::where('id', $detail['location_id'])->first();
                /** Get stock data */
                $stock = Stock::where('site_id', $fromSite->id)->where('catg_id', $productCategory->id)->where('location_id', $location->id)->first();
                $stockBookingSum = StockBooking::where('site_id', $fromSite->id)->where('catg_id', $productCategory->id)->where('location_id', $location->id)->sum('quantity');

                /** Check stock is freeze or not */
                if ($stock->so_flag == 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit transfer, stock product '.$productCategory->catg_name.' is freeze.']);
                }

                /** Check product is active or not */
                if ($productCategory->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit transfer, product '.$productCategory->catg_name.' is not active.']);
                }

                /** Check location is active or not */
                if ($location->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to submit transfer, location '.$location->location_name.' is not active.']);
                }

                /** Validate input qty with stock & booking qty */
                if (($stock->quantity - $stockBookingSum) < $detail['qty']) {
                    Log::warning('Stock '.$productCategory->catg_name.' is not available. Total Transfer Qty: '.$detail['qty'].', Stock Qty: '.$stock->quantity.', Stock Booking Qty: '.$stockBookingSum, ['userId' => $user->id, 'trfId' => $validated['trf_header_id']]);
                    throw ValidationException::withMessages(['detail' => 'Stock '.$productCategory->catg_name.' is not available. Total Transfer Qty: '.$detail['qty'].', Stock Qty: '.$stock->quantity.', Stock Booking Qty: '.$stockBookingSum]);
                }

                /** Insert transfer detail */
                TransferDetail::create([
                    'trf_id' => $trfHeader->id,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'catg_desc' => $productCategory->catg_name,
                    'unit_price' => 0,
                    'quantity' => $detail['qty'],
                    'unit' => $productCategory->unit,
                    'from_location_id' => $location->id,
                    'from_location_code' => $location->location_code,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                /** Add booking qty */
                StockBooking::create([
                    'site_id' => $fromSite->id,
                    'site_code' => $fromSite->site_code,
                    'location_id' => $location->id,
                    'location_code' => $location->location_code,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'quantity' => $detail['qty'],
                    'unit' => $productCategory->unit,
                    'book_type' => 'TRF', /** Transfer */
                    'reference_no' => $trxNumber,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);
            }

            (string) $title = 'Success';
            (string) $message = 'Transfer request successfully submitted with number: '.$trxNumber;
            (array) $data = [
                'trx_number' => $trxNumber,
            ];
            (string) $route = route('list-transfer');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit transfer request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit transfer request', 422, $e);
        }
    }

    /**
     * POST request for approve transfer request
     */
    public function postTrfReqApprove(Request $request)
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::TRANSFER_APPROVAL)) {
            return abort(403, 'Insufficient permission.');
        }
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'trf_header_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusTrfPendingApprove = Status::where('module', 'transfer')->where('flag_value', 1)->first()->flag_value;
        $statusTrfApprove = Status::where('module', 'transfer')->where('flag_value', 2)->first()->flag_value;

        /** Get movement type */
        $movCodeTrfOut = MovementType::where('mov_code', InterfaceClass::TRANSFEROUT_MOVEMENT)->first()?->mov_code;

        /** Get transfer data */
        $trfData = TransferHeader::where('id', $validated['trf_header_id'])->first();
        if (is_null($trfData)) {
            Log::warning('Transfer request not found', ['userId' => $user->id, 'trfId' => $validated['trf_header_id']]);
            throw ValidationException::withMessages(['trf_header_id' => 'Transfer request not found']);
        }

        /** Check if status is pending approval */
        if ($trfData->flag != $statusTrfPendingApprove) {
            Log::warning('Transfer request status is not pending approve', ['userId' => $user->id, 'trfId' => $validated['trf_header_id']]);
            throw ValidationException::withMessages(['trf_header_id' => 'Transfer request status is not pending approve']);
        }

        DB::beginTransaction();
        try {
            /** Get transfer detail */
            $trfDetailData = TransferDetail::where('trf_id', $trfData->id)->get();
            foreach ($trfDetailData as $detail) {
                /** Get stock data */
                $stock = Stock::where('site_id', $trfData->origin_site_id)->where('catg_id', $detail->catg_id)->where('location_id', $detail->from_location_id)->first();
                $productCategory = ProductCategory::where('id', $detail->catg_id)->first();

                /** Check stock is freeze or not */
                if ($stock->so_flag == 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to approve transfer, stock product '.$productCategory->catg_name.' is freeze.']);
                }

                /** Validate stock quantity */
                if ($stock->quantity < $detail->quantity) {
                    Log::warning('Stock is not available. Total Transfer Qty: '.$detail->quantity.', Stock Qty: '.$stock->quantity, ['userId' => $user->id, 'trfId' => $validated['trf_header_id']]);
                    throw ValidationException::withMessages(['detail' => 'Stock is not available. Total Transfer Qty: '.$detail->quantity.', Stock Qty: '.$stock->quantity]);
                }
                $stock->quantity -= $detail->quantity;
                $stock->save();

                /** Generate stock movement */
                StockMovement::create([
                    'mov_date' => date('Y-m-d'),
                    'site_id' => $trfData->origin_site_id,
                    'site_code' => $trfData->origin_site_code,
                    'location_id' => $detail->from_location_id,
                    'location_code' => $detail->from_location_code,
                    'catg_id' => $detail->catg_id,
                    'catg_code' => $detail->catg_code,
                    'quantity' => $detail->quantity * -1, /** Convert to negative amount because transfer out */
                    'unit' => $detail->unit,
                    'mov_code' => $movCodeTrfOut, /** Reference with table movement_types */
                    'purch_price' => 0,
                    'sales_price' => 0,
                    'ref_no' => $trfData->trf_no,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);
            }
            /** Remove booking qty */
            StockBooking::where('book_type', 'TRF')->where('reference_no', $trfData->trf_no)->delete();

            /** Update header */
            $trfData->flag = $statusTrfApprove;
            $trfData->approved_by = $user?->id;
            $trfData->approved_date = date('Y-m-d H:i:s');
            $trfData->updated_by = $user?->id;
            $trfData->save();

            (string) $title = 'Success';
            (string) $message = 'Transfer request successfully approved';

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => null,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when approve transfer request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to approve transfer request', 422, $e);
        }
    }

    /**
     * POST request for reject transfer request
     */
    public function postTrfReqReject(Request $request)
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::TRANSFER_APPROVAL)) {
            return abort(403, 'Insufficient permission.');
        }
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'trf_header_id' => ['required', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusTrfPendingApprove = Status::where('module', 'transfer')->where('flag_value', 1)->first()->flag_value;
        $statusTrfReject = Status::where('module', 'transfer')->where('flag_value', 8)->first()->flag_value;

        /** Get data */
        $trfData = TransferHeader::where('id', $validated['trf_header_id'])->first();
        if (is_null($trfData)) {
            Log::warning('Transfer request not found', ['userId' => $user->id, 'trfId' => $validated['trf_header_id']]);
            throw ValidationException::withMessages(['trf_header_id' => 'Transfer request not found']);
        }

        /** Check if status is pending approval */
        if ($trfData->flag != $statusTrfPendingApprove) {
            Log::warning('Transfer request status is not pending approve', ['userId' => $user->id, 'trfId' => $validated['trf_header_id']]);
            throw ValidationException::withMessages(['trf_header_id' => 'Transfer request status is not pending approve']);
        }

        DB::beginTransaction();
        try {
            /** Update header */
            $trfData->flag = $statusTrfReject;
            $trfData->updated_by = $user?->id;
            $trfData->updated_at = date('Y-m-d H:i:s');
            $trfData->save();

            /** Remove booking qty */
            StockBooking::where('book_type', 'TRF')->where('reference_no', $trfData->trf_no)->delete();

            (string) $title = 'Success';
            (string) $message = 'Transfer request successfully rejected';
            (string) $route = route('list-transfer');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when reject transfer request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to reject transfer request', 422, $e);
        }
    }

    /**
     * GET form transfer list page
     */
    public function listTransferPage(Request $request): View
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::TRANSFER_LIST)) {
            return abort(403, 'Insufficient permission.');
        }
        $user = Auth::user();
        // Log::debug('User is requesting transfer list page', ['userId' => $user?->id, 'userName' => $user?->name, 'remoteIp' => $request->ip()]);

        /** Permission for print document */
        $isReqTrfAllowed = false;
        if (Profile::authorize(InterfaceClass::TRANSFER_CREATE)) {
            $isReqTrfAllowed = true;
        }

        (array) $data = [
            'is_req_trf_allowed' => $isReqTrfAllowed,
        ];

        return view('list-transfer', $data);
    }

    /**
     * GET request for getting list of transfer
     */
    public function getTrfListDatatable(Request $request)
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::TRANSFER_LIST)) {
            return abort(403, 'Insufficient permission.');
        }
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
            'trf_no' => ['nullable', 'string'],
            'from_site' => ['nullable', 'integer'],
            'to_site' => ['nullable', 'integer'],
            'status_id' => ['nullable', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusTrfPendingApprove = Status::where('module', 'transfer')->where('flag_value', 1)->first()->flag_value;

        /** Get sites permission */
        $userSites = UserSite::where('user_id', $user?->id)->get()->pluck('site_code')->toArray();

        /** Prepare for parameters */
        $params = '';
        if (! is_null($validated['from_date'])) {
            $params .= " AND th.trf_date >= '".Carbon::parse($validated['from_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($validated['to_date'])) {
            $params .= " AND th.trf_date <= '".Carbon::parse($validated['to_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($validated['trf_no'])) {
            $params .= " AND th.trf_no ILIKE '%".$validated['trf_no']."%'";
        }
        if (! is_null($validated['from_site'])) {
            // $params .= " AND CAST(orig.site_code AS TEXT) ILIKE '%".$validated['from_site']."%'";
            $params .= " AND th.origin_site_id = ".$validated['from_site'];
        }
        if (! is_null($validated['to_site'])) {
            // $params .= " AND CAST(dest.site_code AS TEXT) ILIKE '%".$validated['to_site']."%'";
            $params .= " AND th.destination_site_id = ".$validated['to_site'];
        }
        if (! is_null($validated['status_id'])) {
            $params .= " AND s.flag_value = ".$validated['status_id'];
        }

        $sql = ("SELECT th.id AS trf_id, th.trf_no, th.trf_date,
                orig.site_code AS site_code_orig, orig.store_code AS store_code_orig,
                dest.site_code AS site_code_dest, dest.store_code AS store_code_dest,
                s.flag_desc AS status, s.flag_value
            FROM transfer_headers th, sites orig, sites dest, status s
            WHERE th.origin_site_id = orig.id
                AND th.destination_site_id = dest.id
                AND th.flag = s.flag_value
                AND s.module = 'transfer'
                AND EXISTS (
                    SELECT 1
                    FROM user_sites us
                    WHERE us.user_id = $user->id
                        AND (us.site_id = orig.id OR us.site_id = dest.id)
                )$params
            ORDER BY th.trf_date DESC");
		$data = DB::select($sql);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("actions", function($row) use($statusTrfPendingApprove, $userSites) {
                $buttons = '<a href="/view-transfer/'.$row->trf_id.'" title="View">
                    <button type="submit" class="btn btn-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                    </button>
                </a>';

                if ($row->flag_value == $statusTrfPendingApprove && Profile::authorize(InterfaceClass::TRANSFER_APPROVAL) && in_array($row->site_code_orig, $userSites)) {
                    $buttons .= '<a href="/approve-transfer/'.$row->trf_id.'" title="Approve">
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

    /**
     * GET view transfer page
     */
    public function viewTransferPage($id): View
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::TRANSFER_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();
        Log::debug('User is request transfer request page', ['userId' => $user?->id, 'userName' => $user?->name, 'trfId' => $id]);

        /** Get status id */
        $statusTrfSubmit = Status::where('module', 'transfer')->where('flag_value', 2)->first()->flag_value;

        /** Get sites permission */
        $userSites = UserSite::where('user_id', $user?->id)->get()->pluck('site_code')->toArray();

        /** Get transfer header data */
        $sqlHeader = "SELECT th.trf_no, th.trf_date, orig.site_code AS site_code_orig, orig.store_code AS store_code_orig, orig.site_description AS site_description_orig,
                dest.site_code AS site_code_dest, dest.store_code AS store_code_dest, dest.site_description AS site_description_dest, th.flag
            FROM transfer_headers th, sites orig, sites dest
            WHERE th.origin_site_id = orig.id
                AND th.destination_site_id = dest.id
                AND th.id = $id
            LIMIT 1";

        /** Get transfer detail data */
        $sqlDetail = "SELECT td.id AS detail_id, td.catg_id, td.catg_code, td.catg_desc,
                td.quantity, td.unit, td.from_location_id, l.location_code, l.location_name
            FROM transfer_details td, locations l
            WHERE td.trf_id = $id
                AND td.from_location_id = l.id
            ORDER BY td.catg_desc";

        /** Permission for print document */
        $isPrintAllowed = false;
        $trfHeader = collect(DB::select($sqlHeader))->first();
        if (Profile::authorize(InterfaceClass::TRANSFER_PRINT) && $trfHeader->flag == $statusTrfSubmit && in_array($trfHeader->site_code_orig, $userSites)) {
            $isPrintAllowed = true;
        }

        (array) $data = [
            'trf_id' => $id,
            'trf_header_data' => collect(DB::select($sqlHeader))->first(),
            'trf_detail_data' => DB::select($sqlDetail),
            'is_print_allowed' => $isPrintAllowed,
        ];

        return view('view-transfer', $data);
    }

    /**
     * GET form transfer approval page
     */
    public function approveTransferPage($id): View
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::TRANSFER_APPROVAL)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();
        Log::debug('User is request transfer request approval page', ['userId' => $user?->id, 'userName' => $user?->name, 'trfId' => $id]);

        /** Get transfer header data */
        $sqlHeader = "SELECT th.id AS trf_id, th.trf_no, th.trf_date,
                orig.site_code AS site_code_orig, orig.store_code AS store_code_orig, orig.site_description AS site_description_orig,
                dest.site_code AS site_code_dest, dest.store_code AS store_code_dest, dest.site_description AS site_description_dest
            FROM transfer_headers th, sites orig, sites dest
            WHERE th.origin_site_id = orig.id
                AND th.destination_site_id = dest.id
                AND th.id = $id
            LIMIT 1";

        /** Get transfer detail data */
        $sqlDetail = "SELECT td.id AS detail_id, td.catg_id, td.catg_code, td.catg_desc,
                td.quantity, td.unit, td.from_location_id, l.location_code, l.location_name
            FROM transfer_details td, locations l
            WHERE td.trf_id = $id
                AND td.from_location_id = l.id
            ORDER BY td.catg_desc";

        (array) $data = [
            'trf_id' => $id,
            'trf_header_data' => collect(DB::select($sqlHeader))->first(),
            'trf_detail_data' => DB::select($sqlDetail),
        ];

        return view('approve-transfer', $data);
    }

    /**
     * GET request for transfer document page
     */
    public function documentTrfPage($id): View
    {
        $user = Auth::user();
        Log::debug('User is request transfer document page', ['userId' => $user?->id, 'userName' => $user?->name, 'trfId' => $id]);

        /** Get transfer header data */
        $sqlHeader = "SELECT th.id AS trf_id, th.trf_no, th.trf_date, th.approved_date,
                orig.site_code AS site_code_orig, orig.store_code AS store_code_orig, orig.site_description AS site_description_orig,
                dest.site_code AS site_code_dest, dest.store_code AS store_code_dest, dest.site_description AS site_description_dest
            FROM transfer_headers th, sites orig, sites dest
            WHERE th.origin_site_id = orig.id
                AND th.destination_site_id = dest.id
                AND th.id = $id
            LIMIT 1";

        /** Get transfer detail data */
        $sqlDetail = "SELECT td.id AS detail_id, td.catg_id, td.catg_code, td.catg_desc,
                td.quantity, td.unit, td.from_location_id, l.location_code, l.location_name
            FROM transfer_details td, locations l
            WHERE td.trf_id = $id
                AND td.from_location_id = l.id
            ORDER BY td.catg_desc";

        (array) $data = [
            'trf_id' => $id,
            'trf_header_data' => collect(DB::select($sqlHeader))->first(),
            'trf_detail_data' => DB::select($sqlDetail),
        ];

        return view('document-transfer', $data);
    }


    public function exportExcelListTransfer(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'dateFromData' => ['nullable', 'date'],
            'dateToData' => ['nullable', 'date'],
            'trfNumber' => ['nullable', 'string'],
            'siteFrom' => ['nullable', 'integer'],
            'siteTo' => ['nullable', 'integer'],
            'status' => ['nullable', 'integer'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }

        return Excel::download(new TransferExport($request), 'list_transfer.xlsx');
    }
}
