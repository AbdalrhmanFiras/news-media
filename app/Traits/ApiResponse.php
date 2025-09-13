<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function successResponse(string $message, mixed $data = null, int $status = 200, array $meta = null, array $links = null,): JsonResponse
    {

        $response = [
            'success' => true,
            'message' => $message,
            'status' => $status,
        ];

        if (!is_null($data)) $response['data'] = $data;
        if (!is_null($meta)) $response['meta'] = $meta;
        if (!is_null($links)) $response['links'] = $links;
        return response()->json($response, $status);
    }

    protected function errorResponse(
        string $message,
        array|object|null $data = null,
        int $status = 400,
        array $errors = []
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'status'  => $status,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
