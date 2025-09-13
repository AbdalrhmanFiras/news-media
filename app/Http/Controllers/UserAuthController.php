<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\JwtAuth;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;

class UserAuthController extends Controller
{
    use ApiResponse;
    use JwtAuth;

    protected $guard = 'user';
    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $publisher = User::create($data);
        return $this->successResponse('User registered successfully.', $publisher, 201);
    }


    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = Auth::guard($this->guard)->attempt($credentials)) {
            return $this->errorResponse('Invalid credentials', null, 401);
        }

        return $this->successResponse('Login successful', [
            'access_token' => $token,
        ]);
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

        return $this->successResponse('Token refreshed', [
            'access_token' => $newToken,
        ]);
    }
}
