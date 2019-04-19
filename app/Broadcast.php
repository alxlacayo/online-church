<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\BroadcastSaved;

class Broadcast extends Model
{
    /**
     * The number of minutes before start time to open the broadcast.
     *
     * @var int
     */
    const MINUTES_BEFORE_START = 10;

    /**
     * The number of minutes after broadcast has ended to keep chat open.
     *
     * @var int
     */
    const MINUTES_AFTER_END = 10;

    /**
     * The duration in seconds for live broadcast.
     *
     * @var int
     */
    const LIVE_BROADCAST_DURATION = 80 * 60;

    /**
     * The broadcast chat is open.
     *
     * @var string
     */
    const BROADCAST_OPEN = 'broadcast_open';

    /**
     * The broadcast is in progress.
     *
     * @var string
     */
    const BROADCAST_IN_PROGRESS = 'broadcast_in_progress';

    /**
     * The broadcast has ended.
     *
     * @var string
     */
    const BROADCAST_ENDED = 'broadcast_ended';

    /**
     * The broadcast chat has closed.
     *
     * @var string
     */
    const BROADCAST_CLOSED = 'broadcast_closed';

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saving' => BroadcastSaving::class
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'starts_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'day', 'time', 'enabled', 'created_at', 'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'day', 'time', 'live', 'enabled'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'live' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * Get the broadcast sermon.
     *
     * @return \App\Sermon
     */
    public function sermon()
    {
        return $this->belongsTo('App\Sermon');
    }

    /**
     * Get the comments for the broadcast.
     * 
     * @return array  \App\BroadcastComment
     */
    public function comments()
    {
        return $this->hasMany('App\BroadcastComment');
    }
}
