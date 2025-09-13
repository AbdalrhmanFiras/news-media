<?php

namespace App\Models;

use App\Enum\ContentStatus;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => ContentStatus::class,
    ];




    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function images()
    {
        return $this->morphMany(Media::class, 'mediable')->where('type', 'image');
    }

    public function videos()
    {
        return $this->morphMany(Media::class, 'mediable')->where('type', 'video');
    }
}
