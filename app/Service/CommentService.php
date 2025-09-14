<?php

namespace App\Service;


use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CommentService
{


    public function createComment(Model $author, Model $commentable, array $data)
    {
        return $author->comments()->create([
            'content'          => $data['content'],
            'commentable_id'   => $commentable->id,
            'commentable_type' => get_class($commentable),
        ]);
    }


    public function updateComment(int $commentId, array $data): Comment
    {
        $comment = Comment::findOrFail($commentId);
        $comment->update([
            'content' => $data['content'],
        ]);

        return $comment;
    }


    public function deleteComment(int $commentId): bool
    {
        $comment = Comment::findOrFail($commentId);
        return $comment->delete();
    }


    public function getComment(int $commentId, array $with = []): Comment
    {
        return Comment::with($with)->findOrFail($commentId);
    }
}
