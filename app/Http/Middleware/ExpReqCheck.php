<?php

namespace App\Http\Middleware;

use App\Models\ExpendingHeader;
use App\Models\UserSite;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ExpReqCheck
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
            // if (!Profile::authorize(InterfaceClass::EXPENDING_LIST)) {
            //     return abort(403, 'Insufficient permission.');
            // }

            /** Check if exp is exists or not */
            (int) $countExpHeader = ExpendingHeader::where('id', $request->route('id'))->count();

            /** If count 0 then abort */
            if ($countExpHeader == 0) {
                return abort(404, 'Not found');
            }

            /** Validate sites */
            $user = Auth::user();
            if (! is_null($user)) {
                $expHeader = ExpendingHeader::where('id', $request->route('id'))->first();

                (int) $countUserSite = UserSite::where('user_id', $user?->id)
                    ->where(function (Builder|QueryBuilder $query) use ($expHeader) {
                        $query->where('site_id', $expHeader->origin_site_id);
                    })->count();

                /** If count 0 then abort */
                if ($countUserSite == 0) {
                    return abort(403, 'Unauthorized action. Please select a expending request in your sites permission to continue.');
                }
            }

            return $next($request);
        } else {
            return redirect()->back()->with('error', 'Please select a expending request first');
        }
    }
}
