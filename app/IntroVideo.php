<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntroVideo extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'enabled', 'updated_at', 'created_at',
    ];
}
