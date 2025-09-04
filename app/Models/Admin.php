<?php

namespace App\Models;

use App\Enum\AdminType;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'role' => AdminType::class
    ];
}
