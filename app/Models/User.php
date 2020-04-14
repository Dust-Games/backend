<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Session;
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

    /*|==========| Relationships |==========|*/

    public function sessions()
    {
        return $this->hasMany(Session::class, 'user_id');
    }
}
