<?php

namespace App\Http\Middleware;

use Closure;
use App\Interfaces\InterfaceClass;
use App\Models\Status;
use App\Models\StockOpnameHeader;
use App\Models\UserSite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StockOpnameInputCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route('id')) {
            /** Get Status ID */
            $statusSoFreeze = Status::where('module', 'stock_opname')->where('flag_value', 1)->first()->flag_value;
            $statusSoStockInput = Status::where('module', 'stock_opname')->where('flag_value', 2)->first()->flag_value;

            /** Check if stock opname is exists or not */
            (int) $countSoHeader = StockOpnameHeader::where('id', $request->route('id'))
                ->where(function (Builder|QueryBuilder $query) use ($statusSoFreeze, $statusSoStockInput) {
                    $query->where('flag', $statusSoFreeze)->orWhere('flag', $statusSoStockInput);
                })->count();

            /** If count 0 then abort */
            if ($countSoHeader == 0) {
                return abort(403, 'Unauthorized action. Please select a stock opname in freeze or stock input state to continue.');
            }

            /** Validate sites */
            $user = Auth::user();
            if (! is_null($user)) {
                $soHeader = StockOpnameHeader::where('id', $request->route('id'))->first();

                (int) $countUserSite = UserSite::where('user_id', $user?->id)->where('site_id', $soHeader->site_id)->count();

                /** If count 0 then abort */
                if ($countUserSite == 0) {
                    return abort(403, 'Unauthorized action. Please select a stock opname in your sites permission to continue.');
                }
            }

            return $next($request);
        } else {
            return redirect()->back()->with('error', 'Please select a stock opname first');
        }
    }
}
