<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Exports\ReceivingExport;
use App\Interfaces\InterfaceClass;
use App\Models\ReceivingHeader;
use App\Http\Requests\StoreReceivingHeaderRequest;
use App\Http\Requests\UpdateReceivingHeaderRequest;
use App\Models\Location;
use App\Models\MovementType;
use App\Models\ProductCategory;
use App\Models\Profile;
use App\Models\ReceivingDetail;
use App\Models\Site;
use App\Models\Status;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\TransferHeader;
use App\Models\UserSite;
use App\Policies\SupplierPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Type\Integer;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use PhpOption\None;

class ReceivingHeaderController extends Controller
{


    public function listReceivingPage() : View
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::RECEIVING_LIST
        )) {
            return abort(403, 'Insufficient permission.');
        }

        return view('list-receiving');
    }

    public function showdata() : View
    {

        if (!Profile::authorize(InterfaceClass::RECEIVING_CREATE)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = auth()->user()->id;

        $dataSite = UserSite::where('user_id', $user)->orderBy('site_code', 'asc')->get();
        $productCategories = ProductCategory::all();
        $supplier = Supplier::all();

        return view('form-receiving', [
            'dataSites' => $dataSite,
            'productCategories' => $productCategories,
            'suppliers' => $supplier
        ]);
    }

    public function getTransferData(Request $request)
    {
        $data = DB::table('transfer_headers')->where('destination_site_id', $request['data_site'])->where('flag', 2)->get();

        return $data;
    }

    public function getProductData(Request $request)
    {
        $productCategories = ProductCategory::where('flag', 1)->get();
        return $productCategories;
    }

    public function getSupplierData(Request $request)
    {
        $data = Supplier::all();
        return $data;
    }

    public function getTransferDetail(Request $request)
    {
        $data = DB::table('transfer_details')->where('trf_id', $request['data_transfer'])->get();

        return $data;
    }

    public function getLocation(Request $request)
    {
        $data = Location::where('flag', 1)->get();

        return $data;
    }

    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $data = DB::table('receiving_headers');

    //     return DataTables::of($data)
    //         ->addIndexColumn()
    //         ->addColumn('action', function($data){
    //             return view('button-list-receiving')->with('data',$data);
    //         })
    //         ->make(true);
    // }

    public function postReceivingReqSubmitSupp(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'receiving_date' => ['required'],
            'type' => ['required', 'string'],
            'site' => ['required', 'integer'],
            'detail' => ['required', 'array'],
            'supplier' => ['required', 'integer'],
            'detail.*.product_id' => ['required', 'integer'],
            'detail.*.location_id' => ['required', 'integer'],
            'detail.*.qty' => ['required', 'integer', 'min:1'],
        ]);


        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();


        // return response()->json($validated);


        /** Get status flag value */
        $statusTrfPendingApprove = Status::where('module', 'receiving')->where('flag_value', 1)->first()->flag_value;
        $statusReceived = Status::where('module', 'transfer')->where('flag_value', 3)->first()->flag_value;

        /** Get sites */
        $destinationSite = Site::where('id', $validated['site'])->first();

        $supplier = Supplier::where('id', $validated['supplier'])->first();

        /** Prepare transaction number, Format: TRF/MM.YY/STORE_CODE/SEQ */
        $receivingDate = Carbon::createFromFormat('d/m/Y', $validated['receiving_date'], 'Asia/Jakarta')->setTimezone('Asia/Jakarta');
        $recDateMonthYear = Carbon::parse($receivingDate)->setTimezone('Asia/Jakarta')->format('m.y');
        $prefixRecxNumber = 'REC/'.$recDateMonthYear.'/'.$destinationSite->store_code.'/';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(rec_no,3), '999')),0) AS no FROM receiving_headers WHERE rec_no LIKE '$prefixRecxNumber%'");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }
        $recxNumber = $prefixRecxNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            /** Insert transfer header */
            $recHeader = ReceivingHeader::create([
                'rec_no' => $recxNumber,
                'rec_date' => $receivingDate,
                'destination_site_id' => $destinationSite->id,
                'destination_site_code' => $destinationSite->site_code,
                'supp_code' => $supplier->supp_code,
                'supp_name' => $supplier->supp_name,
                'flag' => $statusTrfPendingApprove,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            /** Looping details */
            foreach ($validated['detail'] as $detail) {

                $productCategory = ProductCategory::where('id', $detail['product_id'])->first();
                $location = Location::where('id', $detail['location_id'])->first();
                $quantity = (int)$detail['qty'];
                $unit = $productCategory->unit;

                // BELOM PASTI
                $move_code = MovementType::where('mov_code', 'REC')->first();

                /** Check product is active or not */
                if ($productCategory->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to receiving from supplier, product '.$productCategory->catg_name.' is not active.']);
                }

                /** Check location is active or not */
                if ($location->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to receiving from supplier, location '.$location->location_name.' is not active.']);
                }

                /** Insert transfer detail */
                ReceivingDetail::create([
                    'rec_id' => $recHeader->id,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'catg_desc' => $productCategory->catg_name,
                    'unit_price' => 0,
                    'quantity' => $quantity,
                    'unit' => $unit,
                    'dest_location_id' => $location->id,
                    'dest_location_code' => $location->location_code,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                $dataStock = Stock::where('site_id', $destinationSite->id)->where('location_id', $location->id)->where('catg_id', $productCategory->id)->first();

                if ( is_null($dataStock) ) {
                    Stock::create([
                        'site_id' => $destinationSite->id,
                        'site_code' => $destinationSite->site_code,
                        'location_id' => $location->id,
                        'location_code' => $location->location_code,
                        'catg_id' => $productCategory->id,
                        'catg_code' => $productCategory->catg_code,
                        'quantity' => $quantity,
                        'unit' => $unit,
                        'avg_cost' => 0,
                        'so_flag' => 0,
                        'created_by' => $user?->id,
                        'updated_by' => $user?->id,
                    ]);
                } else {
                    if ($dataStock->so_flag == 1) {
                        throw ValidationException::withMessages(['detail' => 'Failed to receiving from supplier, stock product '.$productCategory->catg_name.' is freeze.']);
                    }

                    $dataStock->quantity += $quantity;
                    $dataStock->updated_at = date('Y-m-d H:i:s');
                    $dataStock->updated_by = $user?->id;
                    $dataStock->save();
                }

                // MOVEMENT BELOM
                StockMovement::create([
                    'mov_date' => $receivingDate,
                    'site_id' => $destinationSite->id,
                    'site_code' => $destinationSite->site_code,
                    'location_id' => $location->id,
                    'location_code' => $location->location_code,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'quantity' => $quantity,
                    'unit' => $unit,
                    'mov_code' => $move_code->mov_code,
                    'purch_price' => 0,
                    'sales_price' => 0,
                    'ref_no' => $recxNumber,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

            }

            // $originSite->flag = $statusReceived;
            // $originSite->save();

            (string) $title = 'Success';
            (string) $message = 'Receiving request successfully submitted with number: '.$recxNumber;
            (array) $data = [
                'trx_number' => $recxNumber,
            ];
            (string) $route = route('list-receiving');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit receiving request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit receiving request', 422, $e);
        }



    }

    /**
     * POST request for submitting receiving request
     */
    public function postReceivingReqSubmitTrans(Request $request)
    {
        // $data = [
        //     'title' => 'TEST',
        // ];
        // return response()->json($data);

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'receiving_date' => ['required'],
            'type' => ['required', 'string'],
            'site' => ['required', 'integer'],
            'detail' => ['required', 'array'],
            'transfer' => ['required', 'integer'],

        ]);


        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status flag value */
        $statusTrfPendingApprove = Status::where('module', 'receiving')->where('flag_value', 1)->first()->flag_value;
        $statusReceived = Status::where('module', 'transfer')->where('flag_value', 3)->first()->flag_value;

        /** Get sites */
        $destinationSite = Site::where('id', $validated['site'])->first();
        $originSite = TransferHeader::where('id', $validated['transfer'])->first();

        /** Prepare transaction number, Format: TRF/MM.YY/STORE_CODE/SEQ */
        $receivingDate = Carbon::createFromFormat('d/m/Y', $validated['receiving_date'], 'Asia/Jakarta')->setTimezone('Asia/Jakarta');
        $recDateMonthYear = Carbon::parse($receivingDate)->setTimezone('Asia/Jakarta')->format('m.y');
        $prefixRecxNumber = 'REC/'.$recDateMonthYear.'/'.$destinationSite->store_code.'/';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(rec_no,3), '999')),0) AS no FROM receiving_headers WHERE rec_no LIKE '$prefixRecxNumber%'");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }
        $recxNumber = $prefixRecxNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        // return response()->json($move_code);

        DB::beginTransaction();
        try {
            /** Insert transfer header */
            $recHeader = ReceivingHeader::create([
                'rec_no' => $recxNumber,
                'rec_date' => $receivingDate,
                'origin_site_id' => $originSite->origin_site_id,
                'origin_site_code' => $originSite->origin_site_code,
                'destination_site_id' => $destinationSite->id,
                'destination_site_code' => $destinationSite->site_code,
                'flag' => $statusTrfPendingApprove,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            /** Looping details */
            foreach ($validated['detail'] as $detail) {

                $productCategory = ProductCategory::where('id', $detail['product_id'])->first();
                $location = Location::where('id', $detail['location_id'])->first();
                $quantity = $detail['qty'];
                $unit = $productCategory->unit;

                // BELOM PASTI
                $move_code = MovementType::where('mov_code', 'TRF-IN')->first();

                /** Check product is active or not */
                if ($productCategory->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to receiving from transfer, product '.$productCategory->catg_name.' is not active.']);
                }

                /** Check location is active or not */
                if ($location->flag != 1) {
                    throw ValidationException::withMessages(['detail' => 'Failed to receiving from transfer, location '.$location->location_name.' is not active.']);
                }

                /** Insert transfer detail */
                ReceivingDetail::create([
                    'rec_id' => $recHeader->id,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'catg_desc' => $productCategory->catg_name,
                    'unit_price' => 0,
                    'quantity' => $quantity,
                    'unit' => $unit,
                    'dest_location_id' => $location->id,
                    'dest_location_code' => $location->location_code,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                $dataStock = Stock::where('site_id', $destinationSite->id)->where('location_id', $location->id)->where('catg_id', $productCategory->id)->first();

                if ( is_null($dataStock) ) {
                    Stock::create([
                        'site_id' => $destinationSite->id,
                        'site_code' => $destinationSite->site_code,
                        'location_id' => $location->id,
                        'location_code' => $location->location_code,
                        'catg_id' => $productCategory->id,
                        'catg_code' => $productCategory->catg_code,
                        'quantity' => $quantity,
                        'unit' => $unit,
                        'avg_cost' => 0,
                        'so_flag' => 0,
                        'created_by' => $user?->id,
                        'updated_by' => $user?->id,
                    ]);
                } else {
                    /** Check stock is freeze or not */
                    if ($dataStock->so_flag == 1) {
                        throw ValidationException::withMessages(['detail' => 'Failed to receiving from transfer, stock product '.$productCategory->catg_name.' is freeze.']);
                    }

                    $dataStock->quantity += $quantity;
                    $dataStock->updated_at = date('Y-m-d H:i:s');
                    $dataStock->updated_by = $user?->id;
                    $dataStock->save();
                }

                // MOVEMENT BELOM
                StockMovement::create([
                    'mov_date' => $receivingDate,
                    'site_id' => $destinationSite->id,
                    'site_code' => $destinationSite->site_code,
                    'location_id' => $location->id,
                    'location_code' => $location->location_code,
                    'catg_id' => $productCategory->id,
                    'catg_code' => $productCategory->catg_code,
                    'quantity' => $quantity,
                    'unit' => $unit,
                    'mov_code' => $move_code->mov_code,
                    'purch_price' => 0,
                    'sales_price' => 0,
                    'ref_no' => $recxNumber,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

            }

            $originSite->flag = $statusReceived;
            $originSite->save();

            (string) $title = 'Success';
            (string) $message = 'Receiving request successfully submitted with number: '.$recxNumber;
            (array) $data = [
                'trx_number' => $recxNumber,
            ];
            (string) $route = route('list-receiving');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit receiving request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit receiving request', 422, $e);
        }

    }



    public function getRecListDatatable(Request $request)
    {

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'rec_date' => ['nullable', 'date'],
            'rec_no' => ['nullable', 'string'],
            'supp_site' => ['nullable', 'string']
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Get status id */
        $statusRecPendingApprove = Status::where('module', 'receiving')->where('flag_value', 1)->first()->flag_value;

        /** Prepare for parameters */
        $params = '';

        if (! is_null($validated['rec_date'])) {
            $params .= " AND rh.rec_date = '".Carbon::parse($validated['rec_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }

        if (! is_null($validated['rec_no'])) {
            $params .= " AND rh.rec_no ILIKE '%".$validated['rec_no']."%'";
        }

        if (! is_null($validated['supp_site'])) {
            $params .= " AND (CAST(orig.store_code AS TEXT) ILIKE '%".$validated['supp_site']."%'
                OR rh.supp_name ILIKE '%".$validated['supp_site']."%'
            )";
        }

        // if (! is_null($validated['status_id'])) {
        //     $params .= " AND s.flag_value = ".$validated['status_id'];
        // }

        $sql = ("SELECT rh.id AS rec_id, rh.rec_no, rh.rec_date,
            (CASE WHEN rh.origin_site_id IS NOT NULL THEN orig.store_code
                ELSE rh.supp_name END) AS origin,
            dest.site_code AS to_site_code, dest.store_code AS to_store_code,
            s.flag_desc AS status, s.flag_value
        FROM receiving_headers rh LEFT JOIN sites orig ON orig.id = rh.origin_site_id, sites dest, status s
        WHERE rh.destination_site_id = dest.id
            AND rh.flag = s.flag_value
            AND s.module = 'receiving'
            AND EXISTS (
                SELECT 1
                FROM user_sites us
                WHERE us.user_id = $user->id
                    AND (us.site_id = orig.id OR us.site_id = dest.id)
            )$params
            ORDER BY rh.rec_date DESC");

		$data = DB::select($sql);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("actions", function($row) use($statusRecPendingApprove) {
                $buttons = '<a href="/view-receiving/'.$row->rec_id.'" title="View">
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

    /**
     * GET view transfer page
     */
    public function viewReceivingPage($id): View
    {
        $user = Auth::user();
        // Log::debug('User is request transfer request page', ['userId' => $user?->id, 'userName' => $user?->name, 'recId' => $id]);

        /** Get status id */
        $statusRecSubmit = Status::where('module', 'receiving')->where('flag_value', 1)->first()->flag_value;

        /** Get sites permission */
        $userSites = UserSite::where('user_id', $user?->id)->get()->pluck('site_code')->toArray();

        $sqlRecType = ReceivingHeader::where('id', $id)->first();

        $sqlHeader = "";

        if ( $sqlRecType->origin_site_id != null ) {
            $sqlType = "Transfer";
            $fromSupSite = Site::where('site_code', $sqlRecType->origin_site_code)->first();

            /** Get receiving header data */
            $sqlHeader = "SELECT rh.rec_no, rh.rec_date, dest.site_code AS to_site_code, dest.store_code AS to_store_code, dest.site_description AS to_site_description,
                    orig.site_code AS from_site_code, orig.store_code AS from_store_code, orig.site_description AS from_site_description, rh.flag
                FROM receiving_headers rh, sites orig, sites dest
                WHERE rh.origin_site_id = orig.id
                    AND rh.destination_site_id = dest.id
                    AND rh.id = $id
                LIMIT 1";

        } else {
            $sqlType = "Supplier";
            $fromSupSite = $sqlRecType->supp_name;

            /** Get receiving header data */
            $sqlHeader = "SELECT rh.rec_no, rh.rec_date, dest.site_code AS to_site_code, dest.store_code AS to_store_code, dest.site_description AS to_site_description,
                    rh.supp_name AS supplier_name
                FROM receiving_headers rh, sites dest
                WHERE rh.destination_site_id = dest.id
                    AND rh.id = $id
                LIMIT 1";
        }

        /** Get receiving detail data */
        $sqlDetail = "SELECT rd.id AS detail_id, rd.catg_id, rd.catg_code, rd.catg_desc,
                rd.quantity, rd.unit, rd.dest_location_id, l.location_code, l.location_name
            FROM receiving_details rd, locations l
            WHERE rd.rec_id = $id
                AND rd.dest_location_id = l.id
            ORDER BY rd.catg_desc";

        /** Permission for print document */
        // $isPrintAllowed = false;
        // $trfHeader = collect(DB::select($sqlHeader))->first();
        // if (Profile::authorize(InterfaceClass::TRANSFER_PRINT) && $trfHeader->flag == $statusRecSubmit && in_array($trfHeader->site_code_orig, $userSites)) {
        //     $isPrintAllowed = true;
        // }

        (array) $data = [
            'rec_id' => $id,
            'rec_header_data' => collect(DB::select($sqlHeader))->first(),
            'rec_type' => $sqlType,
            'from_sup_site' => $fromSupSite,
            'rec_detail_data' => DB::select($sqlDetail),
            // 'is_print_allowed' => $isPrintAllowed,
        ];

        return view('view-receiving', $data);
    }

    public function exportExcelListReceiving(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'recNumber' => ['nullable', 'string'],
            'dateRec' => ['nullable', 'string'],
            'suppSite' => ['nullable', 'string'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }

        return Excel::download(new ReceivingExport($request), 'list_receiving.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReceivingHeaderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReceivingHeaderRequest $request, ReceivingHeader $receivingHeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReceivingHeader $receivingHeader)
    {
        //
    }
}
