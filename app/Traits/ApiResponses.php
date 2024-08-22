<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses {

    protected function ok($message)
    {
        return $this->success($message, 200);
    }

    protected function success($message, $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => $message
        ], $statusCode);
    }
}
