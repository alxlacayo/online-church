<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BroadcastComment extends Model
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
    
    /**
     * Get the broadcast that owns the comment.
     */
    public function broadcast()
    {
        return $this->belongsTo('App\Broadcast');
    }
}
