<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{

    /**
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    protected function success(mixed $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status);
    }

    /**
     * @param string $message
     * @param int $status
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function error(string $message, int $status = 400, mixed $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}
