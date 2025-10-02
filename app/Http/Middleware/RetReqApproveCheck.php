<?php

namespace App\Http\Middleware;

use App\Interfaces\InterfaceClass;
use App\Models\Profile;
use App\Models\ReturnHeader;
use App\Models\Status;
use App\Models\UserSite;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RetReqApproveCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route('id')) {
            /** Validate permissions */
            if (!Profile::authorize(InterfaceClass::RETURN_APPROVAL)) {
                return abort(403, 'Insufficient permission.');
            }

            /** Get Status ID */
            $statusRetPendingApprove = Status::where('module', 'return')->where('flag_value', 2)->first()->flag_value;

            /** Check if trf is in pending approve state */
            (int) $countRetHeader = ReturnHeader::where('id', $request->route('id'))->where('flag', $statusRetPendingApprove)->count();

            /** If count 0 then abort */
            if ($countRetHeader == 0) {
                return abort(403, 'Unauthorized action. Please select a return request in pending approve state to continue.');
            }

            /** Validate sites */
            $user = Auth::user();
            if (! is_null($user)) {
                $retHeader = ReturnHeader::where('id', $request->route('id'))->first();

                (int) $countUserSite = UserSite::where('user_id', $user?->id)
                    ->where(function (Builder|QueryBuilder $query) use ($retHeader) {
                        $query->where('site_id', $retHeader->site_id);
                    })->count();

                /** If count 0 then abort */
                if ($countUserSite == 0) {
                    return abort(403, 'Unauthorized action. Please select a return request in your sites permission to continue.');
                }
            }

            return $next($request);
        } else {
            return redirect()->back()->with('error', 'Please select a return request first');
        }
    }
}
