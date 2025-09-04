<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['url', 'type'];

    public function mediable()
    {
        return $this->morphTo();
    }

    public function videos()
    {
        return $this->morphMany(Media::class, 'mediable')->where('type', 'video');
    }
}
