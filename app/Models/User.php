<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Session;
use App\Models\OAuthAccount;
use App\Models\Billing;
use App\Concerns\HasUuidPrimaryKey;

class User extends Authenticatable
{
    use Notifiable, HasUuidPrimaryKey;

    public const MAX_SESSIONS_COUNT = 4;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getPassword()
    {
        return $this->getAttributeFromArray('password');
    }

    public function hasTooManySessions()
    {
        return $this->sessions()->count() >= static::MAX_SESSIONS_COUNT;
    }

    /*|==========| Getters |==========|*/

    public function getUsername()
    {
        return $this->getAttributeFromArray('username');
    }

    public function getEmail()
    {
        return $this->getAttributeFromArray('email');
    }

    public function getAvatar()
    {
        return $this->getAttributeFromArray('avatar');
    }

    public function getCreatedAt()
    {
        return $this->getAttributeFromArray('created_at');
    }

    /*|==========| Relationships |==========|*/

    public function sessions()
    {
        return $this->hasMany(Session::class, 'user_id');
    }

    public function accounts()
    {
        return $this->hasMany(OAuthAccount::class, 'user_id', 'id');
    }

    public function billing()
    {
        return $this->hasOne(Billing::class, 'user_id', 'id');
    }
}
