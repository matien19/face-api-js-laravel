<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {

            $user = Auth::user()->fresh();

            Log::info('Activity middleware', [
                'user_id' => Auth::id(),
                'time' => now()
            ]);
            
            if (
                !$user->last_activity ||
                $user->last_activity->lt(now()->subMinute())
            ) {
                $user->forceFill([
                    'last_activity' => now()
                ])->save();
            }
        }

        return $next($request);
    }
}
