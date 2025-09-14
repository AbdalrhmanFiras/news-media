<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LikesController extends Controller
{
    use ApiResponse;
    // used by user and publisher 
    public function store($postId)
    {
        try {
            $user = Auth::user();
            $post = Post::findOrFail($postId);

            $existing = $post->likes()
                ->where('author_id', $user->id)
                ->where('author_type', get_class($user))
                ->first();

            if ($existing) {
                return $this->errorResponse('You already liked this post.', null, 409);
            }

            $like = $post->likes()->create([
                'author_id'   => $user->id,
                'author_type' => get_class($user),
            ]);

            return $this->successResponse('Post liked successfully.', $like, 201);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Post not found.', null, 404);
        }
    }

    public function destroy($postId)
    {
        try {
            $user = Auth::user();
            $post = Post::findOrFail($postId);

            $post->likes()
                ->where('author_id', $user->id)
                ->where('author_type', get_class($user))
                ->delete();

            return $this->successResponse('Like removed successfully.', null, 200);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Post not found.', null, 404);
        }
    }
}
