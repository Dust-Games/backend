<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class Settings
 * @package App\Models
 * @method static int leagueWeek()
 * @method static int maxLeagueWeeks();
 */
class Settings extends Model
{
    protected $table = 'settings';

    protected $guarded = [];

    /*|==========| Scopes |==========|*/

    /**
     * @param Builder $query
     * @return int
     * @throws \Exception
     */
    public function scopeLeagueWeek($query)
    {
        $start = new Carbon($query->where('key', 'tournament_start_date')->firstOrFail()->value);
        return $start->diffInWeeks(Carbon::now()) + 1;
        // return $query->where('key', 'league_week');
    }

    /**
     * @param Builder $query
     * @return int
     */
    public function scopeMaxLeagueWeeks(Builder $query)
    {
        return $query->where('key', 'max_league_weeks')->firstOrFail()->value;
    }
}
