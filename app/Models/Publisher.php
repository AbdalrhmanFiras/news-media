<?php

namespace App\Models;

use Log;
use App\Models\Like;
use App\Enum\Governorate;
use App\Enum\AccountStatus;
use App\Enum\PublisherVerified;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log as enter;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log as FacadesLog;
use Laravel\Sanctum\HasApiTokens;

class Publisher extends Authenticatable implements JWTSubject
{
    use HasUuids, Notifiable, HasFactory, HasApiTokens;
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'verified' => PublisherVerified::class,
        'account_status' => AccountStatus::class,
        'governorate' => Governorate::class,
    ];

    protected $hidden = [
        'password',
    ];

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }


    public function comments()
    {
        return $this->morphMany(Comment::class, 'author');
    }


    public function profile()
    {
        return $this->hasOne(PublisherProfile::class);
    }


    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }


    public function getJWTCustomClaims()
    {
        return [
            'guard' => 'publishers',
        ];
    }

    public function getJWTIdentifier()
    {
        $identifier = $this->getKey();

        return (string) $identifier;
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
