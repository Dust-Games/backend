<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Session extends Model
{
    protected $table = 'session';

    protected $fillable = [
    	'user_id',
    	'refresh_token_id',
    	'fingerprint',
    	'expires_in',
    ];

    /*|==========| Relationships |==========|*/

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
