<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

trait JwtAuth
{
    use ApiResponse;

    public function login(FormRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = Auth::guard($this->guard)->attempt($credentials)) {
            return $this->errorResponse('Invalid email or password', null, 401);
        }

        return $this->respondWithToken($token, 'Login successful');
    }

    public function logout()
    {
        Auth::guard($this->guard)->logout();

        return $this->successResponse('Logged out successfully');
    }

    public function me()
    {
        return $this->successResponse('User retrieved successfully', Auth::guard($this->guard)->user());
    }

    public function refresh()
    {
        $newToken = Auth::guard($this->guard)->refresh();
        return $this->respondWithToken($newToken, 'Token refreshed');
    }

    protected function respondWithToken(string $token, string $message = 'Token generated')
    {
        return $this->successResponse($message, [
            'access_token' => $token,
            'user'         => Auth::guard($this->guard)->user(),
        ]);
    }
}
