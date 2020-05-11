<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeagueRow;
use App\Models\Settings;
use App\Http\Requests\LeagueRowResource;

class LeagueRowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = LeagueRow::
            where('week', Settings::leagueWeek()->first()->value)
            ->orderByDesc('score')->paginate(static::PER_PAGE, [
                'league.*', 
                DB::raw('row_number() over(order by score desc) as position')
        ]);

        return LeagueRowResource::collection($rows);
    }
}
