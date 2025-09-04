<?php

namespace App\Models;

use App\Enum\ContentStatus;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => ContentStatus::class,
    ];

    public function like()
    {
        return $this->morphMany(Like::class, 'likeabel');
    }

    public function comment()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
