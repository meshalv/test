<?php

namespace App\Http\Controllers;

use App\Services\LuckyService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Exception;

class LuckyController extends Controller
{
    use ApiResponse;

    /**
     * @var LuckyService
     */
    protected LuckyService $service;

    /**
     * @param LuckyService $service
     */
    public function __construct(LuckyService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function main(Request $request)
    {
        try {
            $registration = $request->registration;

            return $this->success([
                'username'     => $registration->username,
                'phone_number' => $registration->phone_number,
                'link'         => url('/lucky/' . $registration->token),
                'expired_at'   => $registration->expired_at,
            ]);
        } catch (Exception $e) {
            Log::error('LuckyController@main error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            return $this->error('Failed to load registration', 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lucky(Request $request)
    {
        try {
            $registration = $request->registration;
            return $this->success(
                $this->service->generateLucky($registration)
            );
        } catch (Exception $e) {
            Log::error('LuckyController@lucky error: '.$e->getMessage());
            return $this->error('Failed to generate lucky number', 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function history(Request $request)
    {
        try {
            $registration = $request->registration;
            return $this->success([
                'items' => $this->service->getHistory($registration)
            ]);
        } catch (Exception $e) {
            Log::error('LuckyController@history error: '.$e->getMessage());
            return $this->error('Failed to fetch history', 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function regenerate(Request $request)
    {
        try {
            $registration = $request->registration;
            return $this->success(
                $this->service->regenerateLink($registration)
            );
        } catch (Exception $e) {
            Log::warning('LuckyController@regenerate error: ' . $e->getMessage());
            return $this->error($e->getMessage(), 410);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivate(Request $request)
    {
        try {
            $registration = $request->registration;
            return $this->success(
                $this->service->deactivateLink($registration)
            );
        } catch (Exception $e) {
            Log::warning('LuckyController@deactivate error: ' . $e->getMessage());
            return $this->error($e->getMessage(), 410);
        }
    }
}
