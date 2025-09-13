<?php

namespace App\Http\Controllers;

use Storage;
use App\Models\Publisher;
use Pest\Plugins\Profile;
use App\Helpers\FileHelper;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PublisherProfile;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PublisherProileResource;
use App\Http\Resources\PublisherProfileResource;
use App\Http\Requests\StorePublisherProfileRequest;
use App\Http\Requests\UpdatePublisherProfileRequest;

class PublisherProfileController extends Controller
{

    use ApiResponse;

    //? public/images/publishers/uuid.png
    public function store(StorePublisherProfileRequest $request)
    {
        $userId = Auth::id();
        $data = $request->validated();
        $data['publisher_id'] = $userId;

        $profile = PublisherProfile::create($data);

        $file = $request->file('avatar');

        $path = FileHelper::storeFileProfile($file, $userId, 'publishers');

        $profile->media()->create([
            'url'  => $path,
            'type' => 'avatar'
        ]);

        $profile->load('media');

        return $this->successResponse(
            'Profile Completed Successfully.',
            new PublisherProfileResource($profile),
            200
        );
    }

    public function show($id)
    {
        $profile = PublisherProfile::with('media')->find($id);

        if (!$profile) {
            return $this->errorResponse('Profile not found', null, 404);
        }

        return $this->successResponse(
            'Profile fetched successfully.',
            new PublisherProfileResource($profile),
            200
        );
    }

    // public function update(UpdatePublisherProfileRequest $request)
    // {
    //     $data = $request->validated();
    //     $profile = Auth::user()->profile;
    //     if (!$profile) return $this->errorResponse('Profile not found', null, 404);
    //     $profile->update($data);
    //     return $this->successResponse('Profile updated sucessfully.', new PublisherProfileResource($profile->load('media')), 200);
    // }
    public function update(UpdatePublisherProfileRequest $request)
    {
        $data = $request->validated();
        $userId = Auth::id();

        $profile = PublisherProfile::where('publisher_id', $userId)
            ->with('media')
            ->first();

        if (!$profile) {
            return $this->errorResponse('Profile not found', null, 404);
        }

        unset($data['avatar']);
        $profile->update($data);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            $path = FileHelper::storeAvatar($file, $profile->id, 'publishers',);


            $profile->media()->updateOrCreate(
                [
                    'type'          => 'avatar',
                    'mediable_id'   => $profile->id,
                    'mediable_type' => PublisherProfile::class,
                ],
                [
                    'url' => $path,
                ]
            );
        }

        return $this->successResponse(
            'Profile updated successfully.',
            new PublisherProfileResource($profile->fresh('media')),
            200
        );
    }
}
