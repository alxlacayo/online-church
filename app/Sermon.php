<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\Sermon\SermonSaved;
use App\Events\Sermon\SermonDeleted;

class Sermon extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => SermonSaved::class,
        'deleted' => SermonDeleted::class
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'publish_on'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'youtube_id', 'speaker_id', 'series_id', 'duration', 'publish_on', 'created_at', 'updated_at'
    ];

    /**
     * Get the speaker that belongs to this sermon.
     */
    public function speaker()
    {
        return $this->belongsTo('App\Speaker');
    }

    /**
     * Get the series that belongs to this sermon.
     */
    public function series()
    {
        return $this->belongsTo('App\Series');
    }

    public function getNotesAttribute($value)
    {
        return $value !== null ? nl2br($value) : null;
    }

    public function scopeBasicInfo($query)
    {
        return $query->select('id', 'title', 'image');
    }
}
