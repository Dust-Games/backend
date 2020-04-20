<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\HasUuidPrimaryKey;
use App\Models\User;

class OAuthAccount extends Model
{
	use HasUuidPrimaryKey;

	protected $table = 'oauth_account';

    protected $fillable = [
    	'user_id', 'oauth_provider_id', 'account_id', 'username', 'avatar'
    ];

    public function getUserKey()
    {
        return $this->getAttributeFromArray('user_id');
    }

    public function getAccountKey()
    {
        return $this->getAttributeFromArray('account_id');
    }

    public function hasUser()
    {
        return (bool) $this->getAttributeFromArray('user_id');
    }

    /*|==========| Relationships |==========|*/

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
