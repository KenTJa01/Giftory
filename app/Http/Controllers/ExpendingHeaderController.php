<?php

namespace App\Http\Controllers;

use App\Models\ExpendingHeader;
use App\Http\Requests\StoreExpendingHeaderRequest;
use App\Http\Requests\UpdateExpendingHeaderRequest;
use App\Models\Location;
use App\Models\ProductCategory;
use App\Models\Stock;
use App\Models\UserSite;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExpendingHeaderController extends Controller
{
    public function show_data(Request $request)
    {
        $userSites = DB::table('users')
            ->where('users.id', auth()->user()->id)
            ->join('user_sites', 'users.id', '=', 'user_sites.user_id')
            ->join('sites', 'sites.id', '=', 'user_sites.site_id')
            ->get();

        return view('form-expending', [
            'userSites' => $userSites
        ]);
    }

    public function index()
    {
        $data = DB::table('expending_headers');
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function($data){
            return view('button-list-expending')->with('data', $data);
        })
        ->make(true);
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
    public function store(StoreExpendingHeaderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpendingHeader $expendingHeader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpendingHeader $expendingHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpendingHeaderRequest $request, ExpendingHeader $expendingHeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpendingHeader $expendingHeader)
    {
        //
    }
}
