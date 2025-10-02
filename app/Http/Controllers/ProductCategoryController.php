<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Interfaces\InterfaceClass;
use App\Models\ProductCategory;
use App\Models\Profile;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class ProductCategoryController extends Controller
{
    public function index()
    {
        if (!Profile::authorize(InterfaceClass::MASTER_PRODUCT_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        $addProductAllowed = false;
        if (Profile::authorize(InterfaceClass::MASTER_PRODUCT_CREATE)) {
            $addProductAllowed = true;
        }

        $productCategory = ProductCategory::orderBy('id', 'asc')->where('flag', 1)->orWhere('flag', 2)->get();
        $units = Unit::where('unit_name', 'PCS')->first();
        return view('master-product-category',[
            'productCategories' => $productCategory,
            'add_product_allowed' => $addProductAllowed,
            'units' => $units
        ]);
    }


    public function getProductListDatatable(Request $request) {

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

        if (! is_null($validated['name'])) {
            $params .= "WHERE CAST(p.catg_name AS TEXT) ILIKE '%".$validated['name']."%'";
        }

        // DB::connection()->enableQueryLog();
        $sql = ("SELECT p.id, p.catg_code, p.catg_name, p.unit, p.flag
            FROM product_categories p $params
            ORDER BY p.id DESC");

		$data = DB::select($sql);
        // $log = DB::DB::getQueryLog();
        // dd($data);

        $canEdit = Profile::authorize(InterfaceClass::MASTER_PRODUCT_EDIT);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("actions", function($row) use ($canEdit) {
                $buttons = '';

                // if (Profile::authorize(InterfaceClass::MASTER_PRODUCT_EDIT)) {
                if ($canEdit) {
                    $buttons .= '
                        <button type="submit" class="btn btn-primary editProductCategory" data-pc="'.$row->id.'" id="btnEditUser">
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

    public function getOldDataProduct(Request $request) {
        $data = ProductCategory::where('id', $request->catgId)->first();
        return $data;
    }

    public function postProductReqSubmit(Request $request) {

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'catg_name' => ['required', 'string'],
        ]);


        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $prefixProdNumber = 'C';

        $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(catg_code,6), '999999')),0) AS no FROM product_categories");
        $data = DB::select($sql);
        foreach ($data as $d) {
            $seqNum = $d->no + 1;
        }

        if ($seqNum <= 999) {
            $catg_code = $prefixProdNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);
        } else {
            $catg_code = $prefixProdNumber.$seqNum;
        }

        $catg_name = $validated['catg_name'];
        $flag = $request->flag_active;

        $unit = Unit::where('unit_name', 'PCS')->first();

        DB::beginTransaction();
        try {

            /** Insert transfer header */
            $catgData = ProductCategory::create([
                'catg_code' => $catg_code,
                'catg_name' => $catg_name,
                'unit' => $unit->unit_name,
                'flag' => $flag,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            (string) $title = 'Success';
            (string) $message = 'Product Category request successfully submitted with product name: '.$catg_name;
            (array) $data = [
                'trx_number' => $catg_name,
            ];
            (string) $route = route('master-product-category');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit user request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit user request', 422, $e);
        }
    }

    public function postProductReqEdit(Request $request) {

        // return $request->all();
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'catg_name_edit' => ['required', 'string'],
        ]);


        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        // $prefixProdNumber = 'C';

        // $sql = ("SELECT COALESCE(MAX(TO_NUMBER(RIGHT(catg_code,3), '999')),0) AS no FROM product_categories");
        // $data = DB::select($sql);
        // foreach ($data as $d) {
        //     $seqNum = $d->no + 1;
        // }

        // $catg_code = $prefixProdNumber.str_pad($seqNum, 3, '0', STR_PAD_LEFT);

        $catg_id = $request->catg_id_edit;
        $catg_name = $validated['catg_name_edit'];
        $flag = $request->flag_active;

        $unit = Unit::where('unit_name', 'PCS')->first();

        DB::beginTransaction();
        try {

            /** Insert transfer header */
            $catgData = ProductCategory::where('id', $catg_id)->first();

            $catgData->catg_name = $catg_name;
            $catgData->flag = $flag;

            $catgData->save();

            (string) $title = 'Success';
            (string) $message = 'Product Category request successfully edited with product name: '.$catg_name;
            (array) $data = [
                'trx_number' => $catg_name,
            ];
            (string) $route = route('master-product-category');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit user request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit user request', 422, $e);
        }

    }

}
