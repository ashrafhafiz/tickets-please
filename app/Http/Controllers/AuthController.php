<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\ApiRegisterRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;
    //
    public function login(ApiLoginRequest $request): JsonResponse
    {
        return $this->ok($request->get('email'));
    }

    public function register(ApiRegisterRequest $request): JsonResponse
    {
        return $this->ok($request->get('email'));
    }
}
