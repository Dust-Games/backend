<?php

namespace App\Models;

use App\Events\LeagueSaved;
use Illuminate\Database\Eloquent\Model;

class LeagueRow extends Model
{
    protected $table = 'league';
    protected $guarded = [];
    protected $dispatchesEvents = [
        'updated' => LeagueSaved::class,
        'saved' => LeagueSaved::class,
    ];

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
