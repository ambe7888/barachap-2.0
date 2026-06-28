<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class ActivityByUser
{

    public function handle($request, Closure $next)
    {

        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            $expiresAt = Carbon::now()->addMinutes(1);
            Cache::put('user-is-online-' . $user->id, true, $expiresAt);

            if ($user->last_seen === null || $user->last_seen->diffInMinutes(now()) > 1) {
                $user->update(['last_seen' => now()]);
            }

        }

        return $next($request);
    }
}
