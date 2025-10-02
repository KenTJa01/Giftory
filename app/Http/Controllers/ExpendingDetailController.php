<?php

namespace App\Http\Controllers;

use App\Models\ExpendingDetail;
use App\Http\Requests\StoreExpendingDetailRequest;
use App\Http\Requests\UpdateExpendingDetailRequest;
use App\Models\ProductCategory;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Integer;

class ExpendingDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = ProductCategory::select('catg_name')->get();
        return $stocks;
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
    public function store(StoreExpendingDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpendingDetail $expendingDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data = DB::table('stocks')
            ->join('product_categories', 'product_categories.id', '=', 'stocks.catg_id')
            ->where('site_id', $id)->get();
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpendingDetailRequest $request, ExpendingDetail $expendingDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpendingDetail $expendingDetail)
    {
        //
    }
}
