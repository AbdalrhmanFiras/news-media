<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

class Media extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['url', 'type'];


    protected $appends = ['full_url'];

    public function getFullUrlAttribute()
    {
        return Storage::disk('public')->url($this->url) . '?v=' . $this->updated_at->timestamp;
    }
    public function mediable()
    {
        return $this->morphTo();
    }
}
