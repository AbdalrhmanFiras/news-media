<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{

    protected $guarded = ['id'];

    public function likeable()
    {
        return $this->morphTo();
    }


    public function publisher()
    {
        return $this->BelongsTo(Publisher::class);
    }
}
