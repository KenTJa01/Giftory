<?php

namespace App\Http\Controllers;

use App\Models\ReceivingDetail;
use App\Http\Requests\StoreReceivingDetailRequest;
use App\Http\Requests\UpdateReceivingDetailRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceivingDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productCategories = ProductCategory::select('catg_name')->get();
        return $productCategories;
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
    public function store(int $id)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $data = DB::table('transfer_headers')->where('destination_site_id', $id)->get();

        return $data;
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
    public function update(UpdateReceivingDetailRequest $request, ReceivingDetail $receivingDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReceivingDetail $receivingDetail)
    {
        //
    }
}
