<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeagueRow;
use App\Models\Settings;
use App\Http\Resources\LeagueClassCollection;
use \DB;

class LeagueRowController extends Controller
{
    private const PER_PAGE = 20;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByWeek($week)
    {
        $rows = LeagueRow::
            where('week', $week)
            ->orderByDesc('score')->paginate(static::PER_PAGE, [
                'league.*', 
                DB::raw('row_number() over(order by score desc) as position')
        ])->groupBy(['class']);

        return new LeagueClassCollection($rows);
    }

    public function getCurrentWeek()
    {
        return response()->json([
            'week' => Settings::leagueWeek()->first()->value,
        ]);
    }
}
