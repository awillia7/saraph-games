<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $casts = [
        'brigades' => 'array',
        'types' => 'array',
        'identifiers' => 'array'
    ];
}
