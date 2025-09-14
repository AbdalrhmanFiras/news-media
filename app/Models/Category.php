<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $guarded = ['id'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function article()
    {
        return $this->hasMany(Article::class);
    }
}
