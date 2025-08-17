<?php

namespace App\Http\Controllers;

use App\Services\RegistrationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Exception;

class RegistrationController extends Controller
{
    use ApiResponse;

    /**
     * @var RegistrationService
     */
    protected $service;

    /**
     * @param RegistrationService $service
     */
    public function __construct(RegistrationService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'username' => 'required|string|max:255',
                'phone_number' => 'required|string|max:20',
            ]);

            $registration = $this->service->create($data);

            return $this->success([
                'link' => url('/lucky/' . $registration->token),
                'expired_at' => $registration->expired_at
            ]);
        } catch (Exception $e) {
            Log::error('RegistrationController@register error: '.$e->getMessage());
            return $this->error('Registration failed', 500);
        }
    }
}
