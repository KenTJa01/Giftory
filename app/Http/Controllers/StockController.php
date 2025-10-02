<?php

namespace App\Http\Controllers;

use App\Exports\StockExport;
use App\Models\Stock;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;
use App\Models\ProfileLocation;
use Carbon\Carbon;
use App\Models\Profile;
use App\Interfaces\InterfaceClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class StockController extends Controller
{

    public function listStock(){
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::STOCK_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        return view('list-stock');
    }

    public function listStockMovement(){
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::STOCK_MOVEMENT_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        return view('movement-stock');
    }

    public function getProductListFilter(Request $request){
        $user = Auth::user();

        $sql = ("SELECT DISTINCT stk.catg_id, pc.catg_name, pc.catg_code
            FROM users u, user_sites us, sites s, stocks stk, product_categories pc
            WHERE u.id = us.user_id
                AND us.site_id = s.id
                AND s.flag = 1
                AND us.user_id = $user->id
                AND stk.site_id = s.id
                AND pc.id = stk.catg_id
            ORDER BY stk.catg_id");
        $data = DB::select($sql);

        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     */
    public function getStockListDatatable(Request $request)
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::STOCK_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        // List Stock
        $user = Auth::user();
        Log::debug('User is requesting get stock list', ['userId' => $user?->id, 'userName' => $user?->name, 'remoteIp' => $request->ip()]);
            /** Validate Input */
        $validate = Validator::make($request->all(), [
            'product' => ['nullable', 'integer'],
            'site' => ['nullable', 'integer'],
            'location' => ['nullable', 'integer'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $profileLocation = ProfileLocation::where('profile_id', $user?->profile_id)->count();

        /** Prepare for parameters */
        $params = '';
        if (! is_null($validated['product'])) {
            $params .= " AND pc.id = ".$validated['product'];
        }
        if (! is_null($validated['site'])) {
            $params .= " AND s.site_id = ".$validated['site'];
        }
        if (! is_null($validated['location'])) {
            $params .= " AND s.location_id = ".$validated['location'];
        }
        if ($profileLocation > 0) {
            $params .= " AND EXISTS (SELECT 1 FROM profile_locations pl WHERE s.location_id = pl.location_id AND pl.profile_id = $user->profile_id)";
        }

        $sql = ("SELECT s.id AS stock_id, s.site_code, sites.store_code, sites.site_description, pc.catg_code, pc.catg_name, l.location_code, l.location_name, s.quantity, s.unit,
                COALESCE((SELECT SUM(sb.quantity) FROM stock_bookings sb
                    WHERE sb.site_id = s.site_id AND sb.catg_id = s.catg_id AND sb.location_id = s.location_id),0) AS book_qty
            FROM stocks s, sites, product_categories pc, locations l
            WHERE s.site_id = sites.id
                AND s.catg_id = pc.id
                AND s.location_id = l.id
                AND EXISTS (
                    SELECT 1
                    FROM user_sites us
                    WHERE us.site_id = sites.id
                        AND us.user_id = $user->id
                )$params
            ORDER BY s.id DESC");
        $data = DB::select($sql);

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    // Stock Movement
    public function getProductListFilterStockMov(){
        $user = Auth::user();

        $sql = ("SELECT DISTINCT sm.catg_id, pc.catg_name, pc.catg_code
            FROM stock_movements sm, users u, user_sites us, sites s, product_categories pc
            WHERE u.id = us.user_id
                AND us.site_id = s.id
                AND s.flag = 1
                AND us.user_id = $user->id
                AND sm.site_id = s.id
                AND pc.id = sm.catg_id
            ORDER BY sm.catg_id");
        $data = DB::select($sql);

        return response()->json($data);
    }

    public function getStockMovementListDatatable(Request $request)
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::STOCK_MOVEMENT_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        $user = Auth::user();
        Log::debug('User is requesting get stock movement list', ['userId' => $user?->id, 'userName' => $user?->name, 'remoteIp' => $request->ip()]);
            /** Validate Input */
        $validate = Validator::make($request->all(), [
            'product' => ['nullable', 'integer'],
            'site' => ['nullable', 'integer'],
            'location' => ['nullable', 'string'],
            'movType' => ['nullable', 'integer'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $profileLocation = ProfileLocation::where('profile_id', $user?->profile_id)->count();

        /** Prepare for parameters */
        $params = '';
        if (! is_null($validated['product'])) {
            $params .= " AND sm.catg_id = ".$validated['product'];
        }
        if (! is_null($validated['site'])) {
            $params .= " AND sm.site_id = ".$validated['site'];
        }
        if (! is_null($validated['location'])) {
            $params .= " AND sm.location_code ILIKE '%".$validated['location']."%'";
        }
        if (! is_null($validated['movType'])) {
            $params .= " AND mt.id = ".$validated['movType'];
            // $params .= " AND sm.mov_code = '".$validated['movType']."'";
        }
        if (! is_null($validated['from_date'])) {
            $params .= " AND sm.mov_date >= '".Carbon::parse($validated['from_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if (! is_null($validated['to_date'])) {
            $params .= " AND sm.mov_date <= '".Carbon::parse($validated['to_date'])->setTimezone('Asia/Jakarta')->format('Y-m-d')."'";
        }
        if ($profileLocation > 0) {
            $params .= " AND EXISTS (SELECT 1 FROM profile_locations pl WHERE sm.location_id = pl.location_id AND pl.profile_id = $user->profile_id)";
        }

        $sql = ("SELECT sm.id, sm.site_code, sm.location_code, sites.store_code, sites.site_description, pc.catg_name, sm.mov_date, sm.mov_code, sm.quantity, CAST(trf.origin_site_code AS TEXT) as from_site, trf.destination_site_code as to_site, sm.ref_no, sm.unit
            FROM stock_movements sm, transfer_headers trf, product_categories pc, sites, movement_types mt
            WHERE sm.mov_code = 'TRF-OUT'
                AND sm.ref_no = trf.trf_no
                AND sm.catg_id = pc.id
                AND sm.site_id = sites.id
                AND sm.mov_code = mt.mov_code
                AND EXISTS (
                    SELECT 1
                    FROM user_sites us
                    WHERE us.site_id = sites.id
                        AND us.user_id = $user->id
                )$params
            UNION ALL
                SELECT sm.id, sm.site_code, sm.location_code, sites.store_code, sites.site_description, pc.catg_name, sm.mov_date, sm.mov_code, sm.quantity, CAST(rec.origin_site_code AS TEXT) as from_site, rec.destination_site_code as to_site, sm.ref_no, sm.unit
                FROM stock_movements sm, receiving_headers rec, product_categories pc, sites, movement_types mt
                WHERE sm.mov_code = 'TRF-IN'
                    AND sm.ref_no = rec.rec_no
                    AND sm.catg_id = pc.id
                    AND rec.origin_site_code is not null
                    AND sm.site_id = sites.id
                    AND sm.mov_code = mt.mov_code
                    AND EXISTS (
                        SELECT 1
                        FROM user_sites us
                        WHERE us.site_id = sites.id
                            AND us.user_id = $user->id
                    )$params
            UNION ALL
                SELECT sm.id, sm.site_code, sm.location_code, sites.store_code, sites.site_description, pc.catg_name, sm.mov_date, sm.mov_code, sm.quantity, rec.supp_name as from_site, rec.destination_site_code as to_site, sm.ref_no, sm.unit
                FROM stock_movements sm, receiving_headers rec, product_categories pc, sites, movement_types mt
                WHERE sm.mov_code = 'REC'
                    AND sm.ref_no = rec.rec_no
                    AND sm.catg_id = pc.id
                    AND rec.origin_site_code is null
                    AND sm.site_id = sites.id
                    AND sm.mov_code = mt.mov_code
                    AND EXISTS (
                        SELECT 1
                        FROM user_sites us
                        WHERE us.site_id = sites.id
                            AND us.user_id = $user->id
                    )$params
            UNION ALL
                SELECT sm.id, sm.site_code, sm.location_code, sites.store_code, sites.site_description, pc.catg_name, sm.mov_date, sm.mov_code, sm.quantity, CAST(exp.origin_site_code AS TEXT) as from_site, null to_site, sm.ref_no, sm.unit
                FROM stock_movements sm, expending_headers exp, product_categories pc, sites, movement_types mt
                WHERE sm.mov_code = 'EXP'
                    AND sm.ref_no = exp.req_no
                    AND sm.catg_id = pc.id
                    AND sm.site_id = sites.id
                    AND sm.mov_code = mt.mov_code
                    AND EXISTS (
                        SELECT 1
                        FROM user_sites us
                        WHERE us.site_id = sites.id
                            AND us.user_id = $user->id
                    )$params
            UNION ALL
                SELECT sm.id, sm.site_code, sm.location_code, sites.store_code, sites.site_description, pc.catg_name, sm.mov_date, sm.mov_code, sm.quantity, CAST(adj.site_code AS TEXT) as from_site, null to_site, sm.ref_no, sm.unit
                FROM stock_movements sm, adjustment_headers adj, product_categories pc, sites, movement_types mt
                WHERE sm.mov_code = 'ADJ'
                    AND sm.ref_no = adj.adj_no
                    AND sm.catg_id = pc.id
                    AND sm.site_id = sites.id
                    AND sm.mov_code = mt.mov_code
                    AND EXISTS (
                        SELECT 1
                        FROM user_sites us
                        WHERE us.site_id = sites.id
                            AND us.user_id = $user->id
                    )$params
            UNION ALL
                SELECT sm.id, sm.site_code, sm.location_code, sites.store_code, sites.site_description, pc.catg_name, sm.mov_date, sm.mov_code, sm.quantity, CAST(so.site_code AS TEXT) as from_site, null to_site, sm.ref_no, sm.unit
                FROM stock_movements sm, stock_opname_headers so, product_categories pc, sites, movement_types mt
                WHERE sm.mov_code = 'SO'
                    AND sm.ref_no = so.so_no
                    AND sm.catg_id = pc.id
                    AND sm.site_id = sites.id
                    AND sm.mov_code = mt.mov_code
                    AND EXISTS (
                        SELECT 1
                        FROM user_sites us
                        WHERE us.site_id = sites.id
                            AND us.user_id = $user->id
                    )$params
            UNION ALL
                SELECT sm.id, sm.site_code, sm.location_code, sites.store_code, sites.site_description, pc.catg_name, sm.mov_date, sm.mov_code, sm.quantity, CAST(sm.site_code AS TEXT) AS from_site, null to_site, sm.ref_no, sm.unit
                FROM stock_movements sm, product_categories pc, sites, movement_types mt
                WHERE sm.mov_code = 'OB'
                    AND sm.catg_id = pc.id
                    AND sm.site_id = sites.id
                    AND sm.mov_code = mt.mov_code
                    AND EXISTS (
                        SELECT 1
                        FROM user_sites us
                        WHERE us.site_id = sites.id
                            AND us.user_id = $user->id
                    )$params
            UNION ALL
                SELECT sm.id, sm.site_code, sm.location_code, sites.store_code, sites.site_description, pc.catg_name, sm.mov_date, sm.mov_code, sm.quantity, ret.supp_name as from_site, ret.site_code as to_site, sm.ref_no, sm.unit
                FROM stock_movements sm, return_headers ret, product_categories pc, sites, movement_types mt
                WHERE sm.mov_code = 'RET'
                    AND sm.ref_no = ret.ret_no
                    AND sm.catg_id = pc.id
                    AND sm.site_id = sites.id
                    AND sm.mov_code = mt.mov_code
                    AND EXISTS (
                        SELECT 1
                        FROM user_sites us
                        WHERE us.site_id = sites.id
                            AND us.user_id = $user->id
                    )$params
        ");
        $data = DB::select($sql);

        return DataTables::of($data)->addIndexColumn()->make(true);
    }

    public function exportExcelListStock(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'product' => ['nullable', 'string'],
            'site' => ['nullable', 'integer'],
            'location' => ['nullable', 'integer'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }

        return Excel::download(new StockExport($request), 'list_stock.xlsx');
    }
}
