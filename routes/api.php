<?php

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PublisherAuthController;
use App\Http\Controllers\PublisherProfileController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



//.                                Publisher
Route::prefix('publisher')->group(function () {

    Route::post('register', [PublisherAuthController::class, 'register']);
    Route::post('login', [PublisherAuthController::class, 'login']);

    Route::middleware('auth:publisher')->group(function () {
        //*                         Auth
        Route::post('logout', [PublisherAuthController::class, 'logout']);
        Route::post('refresh', [PublisherAuthController::class, 'refresh']);
        Route::get('me', [PublisherAuthController::class, 'me']);

        //*                         Profile
        Route::apiResource('profile', PublisherProfileController::class);
        Route::post('profile/update', [PublisherProfileController::class, 'Update']);

        //*                         Post
        Route::apiResource('posts', PostController::class);
        Route::put('posts/{postId}/images', [PostController::class, 'updateImages']);



        //*                         Article
        Route::apiResource('articles', ArticleController::class);
    });
});


//.                                User
Route::prefix('user')->group(function () {

    Route::post('register', [UserAuthController::class, 'register']);
    Route::post('login', [UserAuthController::class, 'login']);

    Route::middleware('auth:user')->group(function () {
        //*                         Auth
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::post('refresh', [UserAuthController::class, 'refresh']);
        Route::get('me', [UserAuthController::class, 'me']);

        //*                         Profile
        Route::middleware('auth:user')->group(function () {
            Route::get('/profile', [UserProfileController::class, 'show']);
            Route::post('/profile', [UserProfileController::class, 'store']);
            Route::post('/profile/update', [UserProfileController::class, 'update']);
        });

        //*                         Post
        // Route::apiResource('posts', PostController::class);
        // Route::put('posts/{postId}/images', [PostController::class, 'updateImages']);



        //*                         Article
        // Route::apiResource('articles', ArticleController::class);
    });
});
