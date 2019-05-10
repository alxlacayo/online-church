<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\Broadcast\BroadcastCreated;
use App\Events\Broadcast\BroadcastSaved;
use Carbon\Carbon;

class Broadcast extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => BroadcastSaved::class
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'opens_at',
        'starts_at',
        'closes_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'day', 'time', 'sermon_id', 'recurring', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'day', 'time', 'description', 'live', 'enabled', 'recurring', 'embed_code', 'image', 'starts_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
        'live' => 'boolean',
        'recurring' => 'boolean'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status'];

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

    /**
     * Get the broadcast status.
     * 
     * @return string
     */
    public function getStatusAttribute()
    {
        return $this->getStatus();
    }

    /**
     * Get the broadcast status.
     * 
     * @return string
     */
    public function getTimeElapsedAttribute()
    {
        return $this->getTimeElapsed();
    }

    /**
     * Get the broadcast status.
     * 
     * @return string
     */
    public function getStatus()
    {
        if (($this->opens_at)->isFuture() || ($this->closes_at)->isPast()) {
            return 'broadcast_closed';
        }

        if (($this->starts_at)->isFuture()) {
            return 'broadcast_open';
        }

        return 'broadcast_in_progress';
    }

    public function isClosed()
    {
        return $this->getStatus() == 'broadcast_closed';
    }

    public function getTimeElapsed()
    {
        $timeElapsed = ($this->starts_at)->diffInSeconds(Carbon::now(), false);

        return $timeElapsed > 0 ? $timeElapsed : 0;
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }
}
