<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Interfaces\InterfaceClass;
use App\Models\Profile;
use App\Models\ProfileLocation;
use App\Models\Status;
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

class DashboardController extends Controller
{
    /**
     * GET request for getting expending pending approve list
     */
    public function getExpPendingApproveList(Request $request)
    {
        $user = Auth::user();
        $data = [];

        if (Profile::authorize(InterfaceClass::EXPENDING_APPROVAL)) {
            /** Get status */
            $statusExpPendingApprove = Status::where('module', 'expending')->where('flag_value', 1)->first()->flag_value;

            /** Get profile location */
            $profileLocation = ProfileLocation::where('profile_id', $user?->profile_id)->count();
            $params = '';

            if ($profileLocation > 0) {
                $params = ' AND EXISTS (SELECT 1 FROM profile_locations pl WHERE pl.location_id = eh.location_id AND pl.profile_id = '.$user?->profile_id.')';
            }

            $sql = ("SELECT eh.id AS exp_id, eh.req_no, eh.req_date, eh.origin_site_code, eh.location_code
                FROM expending_headers eh
                WHERE eh.flag = 1
                    AND EXISTS (
                        SELECT 1 FROM user_sites us
                        WHERE us.site_id = eh.origin_site_id
                            AND us.user_id = $user?->id
                    ) $params");
            $data = DB::select($sql);
        }

        return response()->json($data);
    }

    /**
     * GET request for getting transfer pending approve list
     */
    public function getTrfPendingApproveList(Request $request)
    {
        $user = Auth::user();
        $data = [];

        if (Profile::authorize(InterfaceClass::TRANSFER_APPROVAL)) {
            /** Get status */
            $statusTrfPendingApprove = Status::where('module', 'transfer')->where('flag_value', 1)->first()->flag_value;

            $sql = ("SELECT th.id AS transfer_id, th.trf_no, th.trf_date, th.origin_site_code, th.destination_site_code
                FROM transfer_headers th
                WHERE th.flag = $statusTrfPendingApprove
                    AND EXISTS (
                        SELECT 1 FROM user_sites us
                        WHERE us.site_id = th.origin_site_id
                            AND us.user_id = $user?->id
                    )
                ORDER BY th.id DESC");
            $data = DB::select($sql);
        }

        return response()->json($data);
    }

    /**
     * GET request for getting return pending approve list
     */
    public function getRetPendingApproveList(Request $request)
    {
        $user = Auth::user();
        $data = [];

        if (Profile::authorize(InterfaceClass::RETURN_APPROVAL)) {
            /** Get status */
            $statusRetPendingApprove = Status::where('module', 'return')->where('flag_value', 2)->first()->flag_value;

            $sql = ("SELECT rh.id AS return_id, rh.ret_no, rh.ret_date, rh.site_code
                FROM return_headers rh
                WHERE rh.flag = $statusRetPendingApprove
                    AND EXISTS (
                        SELECT 1 FROM user_sites us
                        WHERE us.site_id = rh.site_id
                            AND us.user_id = $user?->id
                    )
                ORDER BY rh.id DESC");
            $data = DB::select($sql);
        }

        return response()->json($data);
    }

    /**
     * GET request for getting transfer approve list
     */
    public function getTrfApproveList(Request $request)
    {
        $user = Auth::user();
        $data = [];

        if (Profile::authorize(InterfaceClass::RECEIVING_CREATE)) {
            /** Get status */
            $statusTrfApprove = Status::where('module', 'transfer')->where('flag_value', 2)->first()->flag_value;

            $sql = ("SELECT th.id AS transfer_id, th.trf_no, th.trf_date, th.origin_site_code, th.destination_site_code
                FROM transfer_headers th
                WHERE th.flag = $statusTrfApprove
                    AND EXISTS (
                        SELECT 1 FROM user_sites us
                        WHERE us.site_id = th.destination_site_id
                            AND us.user_id = $user?->id
                    )
                ORDER BY th.id DESC");
            $data = DB::select($sql);
        }

        return response()->json($data);
    }
}
