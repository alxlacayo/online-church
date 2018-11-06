<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HostComment extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 'updated_at',
    ];
    
    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
