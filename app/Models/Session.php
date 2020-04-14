<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Session extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'session';

    protected $fillable = [
    	'id',
    	'user_id',
    	'refresh_token_id',
    	'fingerprint',
    	'expires_at',
    ];

    /*|==========| Relationships |==========|*/

    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
