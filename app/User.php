<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The sizes of profile pictures to create on upload.
     *
     * @var array
     */
    const PROFILE_PICTURE_SIZES = [
        'large' => 320,
        'medium' => 120,
        'small' => 80
    ];

    private $profilePictureSize = 'medium';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email', 'password', 'disabled', 'remember_token', 'updated_at', 'created_at', 'email_verified_at', 'roles',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_host'];

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    /**
     * Check if a user has a specific role.
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    } 

    /**
     * Check if a user is a host.
     *
     * @return boolean
     */
    public function isHost()
    {
        return $this->hasRole('Host');
    }

    /**
     * Get the profile picture.
     *
     * @param  string  $value
     * @return string
     */
    public function getProfilePictureAttribute($value)
    {
        $path = $value == null
            ? '/images/profile_picture.png'
            : '/storage/users/' . $this->id . '/profile_pictures/' . $this->profilePictureSize . '/' . $value;

        return asset($path);
    }

    /**
     * Get the host flag for the user.
     *
     * @return bool
     */
    public function getIsHostAttribute($value)
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains('name', 'Host');
        } else {
            return $this->isHost();
        }
    }

    /**
     * Set the size of the profile picture.
     *
     * @param  string  $size
     */
    public function setProfilePictureSize($size)
    {
        $this->profilePictureSize = $size;
    }
}
