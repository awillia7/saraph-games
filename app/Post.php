<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = false;

    protected $casts = [
        'published' => 'date:m/d/Y'
    ];
}
