<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The users that belong to the role.
     *
     * @return \App\User
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
