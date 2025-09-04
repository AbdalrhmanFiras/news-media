<?php

namespace App\Models;

use App\Enum\ContentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasUuids;

    protected $casts = [
        'status' => ContentStatus::class,
    ];

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function like()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comment()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function videos()
    {
        return $this->morphMany(Media::class, 'mediable')->where('type', 'video');
    }

    public function images()
    {
        return $this->morphMany(Media::class, 'mediable')->where('type', 'image');
    }
    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }
}
