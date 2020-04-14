<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Concerns\HasUuidPrimaryKey;

class Session extends Model
{
    use HasUuidPrimaryKey;

    protected $table = 'session';

    protected $fillable = [
    	'user_id',
    	'refresh_token_id',
    	'fingerprint',
    	'expires_at',
    ];

    public function tokenExpired()
    {
        return time() >= $this->getAttributeFromArray('expires_at');
    }

    /*|==========| Relationships |==========|*/

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
