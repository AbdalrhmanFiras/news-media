<?php

namespace App\Http\Controllers;

use Exception;
use App\Traits\JwtAuth;
use App\Helpers\FileHelper;
use App\Models\UserProfile;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UserProfileResource;
use App\Http\Requests\StoreUserProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserProfileController extends Controller
{
    use ApiResponse;

    public function index() {}

    public function store(StoreUserProfileRequest $request)
    {
        $userId = Auth::id();
        $data = $request->validated();
        $data['user_id']  = $userId;
        unset($data['avatar']);

        $profile = UserProfile::create($data);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = FileHelper::storeFileProfile($file, $userId, 'users');
            $profile->media()->create([
                'url'  => $path,
                'type' => 'avatar'
            ]);
        }
        $profile->load('media');
        return $this->successResponse(
            'Profile Completed Successfully.',
            new UserProfileResource($profile),
            201
        );
    }


    public function show()
    {
        $userId = Auth::id();

        try {
            $profile = UserProfile::with('media')
                ->where('user_id', $userId)
                ->firstOrFail();

            return $this->successResponse(
                'User profile fetched successfully',
                new UserProfileResource($profile),
                200
            );
        } catch (ModelNotFoundException) {
            return $this->errorResponse('User does not have a profile yet.', null, 404);
        } catch (Exception) {
            abort(500, 'internel server error');
        }
    }


    public function update(UpdateUserProfileRequest $request)
    {
        try {
            $userId = Auth::id();
            $data = $request->validated();

            $profile = UserProfile::with('media')
                ->where('user_id', $userId)
                ->firstOrFail();

            unset($data['avatar']);
            $profile->update($data);

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');

                $path = FileHelper::storeAvatar($file, $profile->id, 'users');

                $profile->media()->updateOrCreate(
                    [
                        'type'          => 'avatar',
                        'mediable_id'   => $profile->id,
                        'mediable_type' => UserProfile::class,
                    ],
                    [
                        'url' => $path,
                    ]
                );
            }

            $profile->load('media');

            return $this->successResponse(
                'User profile updated successfully.',
                new UserProfileResource($profile),
                200
            );
        } catch (ModelNotFoundException) {
            return $this->errorResponse('User does not have a profile yet.', null, 404);
        } catch (Exception) {
            abort(500, 'internel server error');
        }
    }
}
