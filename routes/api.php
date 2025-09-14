<?php


use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PublisherAuthController;
use App\Http\Controllers\PublisherProfileController;

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
        Route::middleware('auth:publisher')->group(function () {
            Route::get('/profile', [PublisherProfileController::class, 'show']);
            Route::post('/profile', [PublisherProfileController::class, 'store']);
            Route::post('profile/update', [PublisherProfileController::class, 'Update']);
        });
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
        //                                  Post , comments , likes
        Route::post('/post/{postId}/comment', [UserCommentController::class, 'store']);
        Route::put('/post/{postId}/comment/{commentId}', [UserCommentController::class, 'update']);
        Route::get('/post/comment/{commentId}', [UserCommentController::class, 'show']);
        Route::delete('/post/comment/{commentId}', [UserCommentController::class, 'delete']);

        Route::post('post/{post}/like', [LikesController::class, 'store']);
        Route::delete('post/{post}/like', [LikesController::class, 'destroy']);



        //*                         Article
        // Route::apiResource('articles', ArticleController::class);
    });
});

Route::apiResource('category', CategoryController::class);



Route::fallback(function () {
    return response()->json([
        'success' => false,

        'message' => 'Page Not Found. If error persists, contact info@website.com'
    ], 404);
});
