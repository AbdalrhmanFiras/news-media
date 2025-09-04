<?php

namespace App\Models;

use App\Models\Like;
use App\Enum\Governorate;
use App\Enum\AccountStatus;
use App\Enum\PublisherVerified;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $casts = [
        'verified' => PublisherVerified::class,
        'account_status' => AccountStatus::class,
        'governorate' => Governorate::class,
    ];

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function profile()
    {
        return $this->hasOne(PublisherProfile::class);
    }
}
