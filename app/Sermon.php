<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sermon extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'publish_on'
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

    public function getNotesAttribute($value) {
        return nl2br($value);
    }
}
