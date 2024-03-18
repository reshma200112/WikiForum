<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccessPrevention
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user->type == 2) {
            return response()->json([
                "message" => "Access Denied",
                'error_code' => 5000
            ], 400);
        }
        if ($user->status == 2) {
            return response()->json([
                "message" => "Account disabled",
                'error_code' => 5001
            ], 405);
        }
        return $next($request);
    }
}
