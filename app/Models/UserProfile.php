<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserProfile extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $appends = ['full_name'];
    protected function getFullNameAttribute()
    {
        return $this->first_name . $this->last_name;
    }

    protected $casts = [
        'bith_date' => 'datetime',
    ];

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
