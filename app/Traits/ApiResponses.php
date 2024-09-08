<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{

    protected function ok($message, $data = [])
    {
        return $this->success($message, $data, 200);
    }

    protected function success($message, $data, $statusCode): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    protected function error($errors = [], $statusCode = null): JsonResponse
    {
        if (is_string($errors)) {
            return response()->json([
                'message' => $errors,
                'status' => $statusCode
            ], $statusCode);
        }

        return response()->json([
            'errors' => $errors
        ]);
    }

    protected function notAuthorized($message)
    {
        return $this->error([
            'status' => 401,
            'message' => $message,
            'source' => '',
        ]);
    }
}
