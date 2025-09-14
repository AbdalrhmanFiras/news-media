<?php

namespace App\Models;

use App\Enum\ContentStatus;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{

    public function scopeByPublisher($query, $id)
    {
        return $query->where('publisher_id', $id);
    }

    protected $guarded = ['id'];

    protected $casts = [
        'status' => ContentStatus::class,
    ];

    public function categorey()
    {
        return $this->belongsTo(Category::class);
    }


    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function author()
    {
        return $this->morphTo();
    }

    public function comments()
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
