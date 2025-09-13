<?php

namespace App\Http\Controllers;

use App\Traits\JwtAuth;
use App\Models\Publisher;
use App\Helpers\FileHelper;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterPublisherRequest;

class PublisherAuthController extends Controller
{
    use ApiResponse;
    use JwtAuth;

    protected $guard = 'publisher';
    public function register(RegisterPublisherRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $publisher = Publisher::create($data);
        //? app/public/images/licenses/uuid.png
        $path = FileHelper::storeFileRegister(
            $request->file('license_image'),
            'images',
            'licenses'
        );

        $publisher->media()->create([
            'type' => 'licenses',
            'url'  => $path,
        ]);

        $publisher->load('media');

        return $this->successResponse('Publisher registered successfully.', $publisher, 201);
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
