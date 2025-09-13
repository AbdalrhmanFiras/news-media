<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublisherProfile extends Model
{

    protected $guarded = ['id'];

    protected $casts = [
        'bith_date' => 'datetime',
    ];

    protected $appends = ['full_name'];

    protected function getFullNameAttribute()
    {
        return $this->first_name . $this->last_name;
    }


    public function getFullUrlAttribute()
    {
        return Storage::disk('public')->url($this->url) . '?v=' . now()->timestamp;
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }


    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
}
