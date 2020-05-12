<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeagueRow extends Model
{
    protected $table = 'league';
    protected $guarded = [];

    public function getRouteKeyName()
    {
    	return 'account_id';
    }

    /*|==========| Getters |==========|*/

    public function getAccountKey()
    {
    	return $this->getAttributeFromArray('account_id');
    }
}
