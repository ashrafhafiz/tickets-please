<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Permissions\Api\V1\Abilities;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Requests\Api\RegisterUserRequest;

class AuthController extends Controller
{
    use ApiResponses;
    //
    public function login(LoginUserRequest $request): JsonResponse
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid Credentials', 401);
        }

        $user = User::firstWhere('email', $request->email);

        return $this->ok('Authenticated', [
            'token' => $user->createToken(
                'API token for ' . $user->email,
                // ['*'],
                Abilities::getAbilities($user),
                now()->addMonth()
            )->plainTextToken
        ]);
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        return $this->ok($request->get('email'));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('Logged out');
    }
}
