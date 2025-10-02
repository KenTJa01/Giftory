<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Interfaces\InterfaceClass;
use App\Models\Profile;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SiteController extends Controller
{
    public function index()
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::MASTER_SITE_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        return view('master-site');
    }
}
