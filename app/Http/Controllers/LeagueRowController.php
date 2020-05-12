<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeagueRow;
use App\Models\Settings;
use App\Http\Resources\LeagueClassCollection;
use \DB;
use App\Services\LeagueRowService;

class LeagueRowController extends Controller
{
    private const PER_PAGE = 40;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByWeek($week, LeagueRowService $service)
    {
        $rows = $service->getRowsByWeek($week, static::PER_PAGE);

        $rows = $rows->groupBy(['class']);

        return new LeagueClassCollection($rows);
    }

    public function getCurrentWeek()
    {
        return response()->json([
            'week' => Settings::leagueWeek()->first()->value,
        ]);
    }
}
