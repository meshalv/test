<?php

namespace App\Http\Middleware;

use App\Models\Registration;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;


class CheckLuckyLink
{
    use ApiResponse;

    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->route('token');
        $registration = Registration::where('token', $token)->first();

        if (!$registration) {
            return $this->error('Link not found', 404);
        }

        if (!$registration->is_active || Carbon::now()->gt($registration->expired_at)) {
            return $this->error('Link is deactivated or expired', 410);
        }

        $request->merge(['registration' => $registration]);

        return $next($request);
    }
}
