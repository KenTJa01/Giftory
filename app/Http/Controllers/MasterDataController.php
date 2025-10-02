<?php

namespace App\Http\Controllers;

use App\Interfaces\InterfaceClass;
use App\Models\Location;
use App\Models\MovementType;
use App\Models\Profile;
use App\Models\Site;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class MasterDataController extends Controller
{
    /**
     * GET request for getting all user sites permissions
     */
    public function getAllUserSitePermission(Request $request) {
        $user = Auth::user();

        $suprUser = Profile::where('profile_code', InterfaceClass::SUPERUSERPROFILE)->first();

        if ($user->profile_id == $suprUser->id) {
            $sql = ("SELECT s.id AS site_id, s.site_code, s.store_code, s.site_description
                FROM sites s
                WHERE s.flag = 1
                ORDER BY s.site_code");
        } else {
            $sql = ("SELECT s.id AS site_id, s.site_code, s.store_code, s.site_description
                FROM users u, user_sites us, sites s
                WHERE u.id = us.user_id
                    AND us.site_id = s.id
                    AND s.flag = 1
                    AND u.id = $user->id
                ORDER BY s.site_code");
        }
		$data = DB::select($sql);

        return response()->json($data);
    }


    /**
     * GET request for getting list status for transfer
     */
    public function getListStatusForRec(Request $request) {
        $user = Auth::user();

        $data = Status::where('module', 'receiving')->orderBy('flag_value')->get();

        return response()->json($data);
    }


    /**
     * GET request for getting list status for transfer
     */
    public function getListStatusForTrf(Request $request) {
        $user = Auth::user();

        $data = Status::where('module', 'transfer')->orderBy('flag_value')->get();

        return response()->json($data);
    }
    public function getListLocations(Request $request) {
        $user = Auth::user();

        $data = Location::where('flag', 1)->orderBy('id')->get();

        return response()->json($data);
    }

    public function getListMovType(Request $request) {
        $user = Auth::user();

        $data = MovementType::orderBy('id')->get();

        return response()->json($data);
    }

    public function getListSitesDatatable(Request $request) {
        $user = Auth::user();

         /** Validate Input */
         $validate = Validator::make($request->all(), [
            'site_code' => ['nullable', 'integer'],
            'store_code' => ['nullable', 'string'],
            'site_desc' => ['nullable', 'string'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Prepare for parameters */
        $params = '';
        if (! is_null($validated['site_code'])) {
            $params .= " AND CAST(site_code AS TEXT) ILIKE '%".$validated['site_code']."%'";
        }
        if (! is_null($validated['store_code'])) {
            $params .= " AND store_code ILIKE '%".$validated['store_code']."%'";
        }
        if (! is_null($validated['site_desc'])) {
            $params .= " AND site_description ILIKE '%".$validated['site_desc']."%'";
        }

        $sql = ("SELECT site_code, store_code, site_description, flag
            FROM sites
            WHERE flag = 1
            $params
            ORDER BY site_code ASC");
        $data = DB::select($sql);

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * GET request for getting list status for stock opanme
     */
    public function getListStatusForStockOpname(Request $request) {
        $user = Auth::user();

        $data = Status::where('module', 'stock_opname')->orderBy('flag_value')->get();

        return response()->json($data);
    }
}
