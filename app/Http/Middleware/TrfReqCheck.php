<?php

namespace App\Http\Middleware;

use Closure;
use App\Interfaces\InterfaceClass;
use App\Models\Profile;
use App\Models\Status;
use App\Models\TransferHeader;
use App\Models\UserSite;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrfReqCheck
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
            if (!Profile::authorize(InterfaceClass::TRANSFER_LIST)) {
                return abort(403, 'Insufficient permission.');
            }

            /** Check if trf is exists or not */
            (int) $countTrfHeader = TransferHeader::where('id', $request->route('id'))->count();

            /** If count 0 then abort */
            if ($countTrfHeader == 0) {
                return abort(404, 'Not found');
            }

            /** Validate sites */
            $user = Auth::user();
            if (! is_null($user)) {
                $trfHeader = TransferHeader::where('id', $request->route('id'))->first();

                (int) $countUserSite = UserSite::where('user_id', $user?->id)
                    ->where(function (Builder|QueryBuilder $query) use ($trfHeader) {
                        $query->where('site_id', $trfHeader->origin_site_id)->orWhere('site_id', $trfHeader->destination_site_id);
                    })->count();

                /** If count 0 then abort */
                if ($countUserSite == 0) {
                    return abort(403, 'Unauthorized action. Please select a transfer request in your sites permission to continue.');
                }
            }

            return $next($request);
        } else {
            return redirect()->back()->with('error', 'Please select a transfer request first');
        }
    }
}
