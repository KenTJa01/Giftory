<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Interfaces\InterfaceClass;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Profile::authorize(InterfaceClass::MASTER_SUPPLIER_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        $addSupplierAllowed = false;
        if (Profile::authorize(InterfaceClass::MASTER_SUPPLIER_CREATE)) {
            $addSupplierAllowed = true;
        }

        return view('master-supplier',[
            'add_supplier_allowed' => $addSupplierAllowed
        ]);
    }

    public function getSupplierListDatatable(Request $request)
    {

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'name' => ['nullable', 'string'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Prepare for parameters */
        $params = '';

        if (!is_null($validated['name'])) {
            $params .= "WHERE CAST(s.supp_name AS TEXT) ILIKE '%".$validated['name']."%'";
        }

        // DB::connection()->enableQueryLog();
        $sql = ("SELECT s.id, s.supp_code, s.supp_name
            FROM suppliers s $params
            ORDER BY s.id DESC");

		$data = DB::select($sql);
        // $log = DB::DB::getQueryLog();
        // dd($data);

        $canEdit = Profile::authorize(InterfaceClass::MASTER_SUPPLIER_EDIT);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("actions", function($row) use ($canEdit) {
                $buttons = '';

                // if (Profile::authorize(InterfaceClass::MASTER_PRODUCT_EDIT)) {
                if ($canEdit) {
                    $buttons .= '
                        <button type="submit" class="btn btn-primary editSupplier" data-s="'.$row->id.'" id="btnEditSupplier">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                            </svg>
                        </button>';
                }

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);

    }

    public function postSupplierReqSubmit(Request $request)
    {

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'supp_name' => ['required', 'string'],
        ]);


        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $prefixProdNumber = 'S';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(supp_code,3), '999')),0) AS no FROM suppliers");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }

        $supp_code = $prefixProdNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        $supp_name = preg_replace("/[^a-zA-Z0-9]/", "", $validated['supp_name']);

        DB::beginTransaction();
        try {

            /** Insert transfer header */
            $suppData = Supplier::create([
                'supp_code' => $supp_code,
                'supp_name' => $supp_name,
                'flag' => 1,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            (string) $title = 'Success';
            (string) $message = 'Supplier request successfully submitted with product name: '.$supp_name;
            (array) $data = [
                'trx_number' => $supp_name,
            ];
            (string) $route = route('master-supplier');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit supplier request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit supplier request', 422, $e);
        }

    }

    public function getOldDataSupplier(Request $request)
    {

        $data = Supplier::where('id', $request->suppId)->first();
        return $data;

    }

    public function postSupplierReqEdit(Request $request)
    {

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'supp_name_edit' => ['required', 'string'],
        ]);


        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $supp_id = $request->supp_id_edit;
        $supp_name = $validated['supp_name_edit'];

        DB::beginTransaction();
        try {

            /** Insert transfer header */
            $suppData = Supplier::where('id', $supp_id)->first();

            $suppData->supp_name = $supp_name;

            $suppData->save();

            (string) $title = 'Success';
            (string) $message = 'Supplier request successfully edited with product name: '.$supp_name;
            (array) $data = [
                'trx_number' => $supp_name,
            ];
            (string) $route = route('master-supplier');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit supplier request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit supplier request', 422, $e);
        }

    }

}
