<?php

namespace App\Models;

use App\Models\User\Role;
use App\Models\User\SubRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Concerns\HasUuidPrimaryKey;
use App\Notifications\VerifyEmail;
use App\Concerns\HasRole;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasUuidPrimaryKey, HasRole;

    public const MAX_SESSIONS_COUNT = 4;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'role_id',
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

    public function getRole()
    {
        return $this->getAttributeFromArray('role_id');
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

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function subRoles()
    {
        return $this->belongsToMany(SubRole::class);
    }

    public function currencyAccounts()
    {
        return $this->morphMany(CurrencyAccount::class, 'owner');
    }

    public function orders()
    {
        return $this->hasManyThrough(
            Order::class, CurrencyAccount::class, 'owner_id', 'currency_account_id',
        );
    }
    /*|====================|*/

    public function sendEmailVerificationNotification()
    {
        $this->notify((new VerifyEmail)->locale(\App::getLocale()));
    }
}
