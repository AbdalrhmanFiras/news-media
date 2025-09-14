<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserCommentResource;
use App\Http\Requests\StoreUserCommentRequest;
use App\Http\Requests\UpdateUserCommentRequest;
use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserCommentController extends Controller
{
    use ApiResponse;

    public function store(StoreUserCommentRequest $request, $postId)
    {
        try {
            $user = Auth::user();
            $post = Post::findOrFail($postId);
            $data = $request->validated();
            $comment = $user->comments()->create([
                'content' => $data['content'],
                'commentable_id' => $post->id,
                'commentable_type' => Post::class
            ]);

            $comment->load('commentable.media');

            return $this->successResponse('Comment Add successfully.', new UserCommentResource($comment), 201);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Post not found.', null, 404);
        } catch (Exception) {
            abort(500, 'internel server error');
        }
    }


    public function show($commentId)
    {
        try {
            $userId = Auth::id();
            $comment = Comment::byAuthor($userId)->with('commentable.media')
                ->where('id', $commentId)
                ->firstOrFail();
            return $this->successResponse('Comment fetched successfully.', new UserCommentResource($comment), 200);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Comment not found.', null, 404);
        } catch (Exception) {
            abort(500, 'internel server error');
        }
    }


    public function update(UpdateUserCommentRequest $request, $postId, $commentId)
    {
        try {
            $userId = Auth::id();
            $data = $request->validated();
            $comment = Comment::byAuthor($userId)->where('id', $commentId)
                ->where('commentable_id', $postId)
                ->where('commentable_type', Post::class)
                ->firstOrFail();

            $comment->update([
                'content' => $data['content']
            ]);

            return $this->successResponse('Comment updated successfully.', new UserCommentResource($comment), 200);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Comment not found.', null, 404);
        } catch (Exception) {
            abort(500, 'internel server error');
        }
    }


    public function delete($commentId)
    {
        try {
            $userId = Auth::id();
            $comment = Comment::byAuthor($userId)
                ->where('id', $commentId)
                ->firstOrFail();

            $comment->delete();

            return $this->successResponse('Comment deleted successfully.', null, 200);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Comment not found.', null, 404);
        } catch (Exception) {
            abort(500, 'internel server error');
        }
    }
}
