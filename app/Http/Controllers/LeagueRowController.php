<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeagueRow;
use App\Models\Settings;
use App\Http\Resources\LeagueClassCollection;
use \DB;
use App\Services\LeagueRowService;
use App\Http\Resources\LeagueRowResource;

class LeagueRowController extends Controller
{
    private const PER_PAGE = 10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByWeek(Request $req, $week, LeagueRowService $service)
    {
        if ($req->input('by_class')) {

            $query = LeagueRow::query();

            $this->filterQuery($query, $req);

            $rows = $service->getRowsByClass(
                $req->input('by_class'),
                static::PER_PAGE, 
                $week,
                $query
            );

            return LeagueRowResource::collection($rows);

        } else {        
            $rows = $service->getRowsByWeek($week, static::PER_PAGE);

            return new LeagueClassCollection($rows);
        }  
    }

    public function getCurrentWeek()
    {
        return response()->json([
            'week' => Settings::leagueWeek()->first()->value,
        ]);
    }

    private function filterQuery($query, $request)
    {
        $input = $request->input();
        $callbacks = $this->getFilterCallbacks();

        foreach ($input as $key => $value) {
            if (array_key_exists($key, $callbacks)) {
                $callbacks[$key]($query, $value);
            }
        }
    }

    private function getFilterCallbacks()
    {
        return [
            'order_by' => function ($query, $value) {
                if (in_array($value, ['total_score', 'score', 'username'])) {
                    $query->orderBy($value);
                }
            }
        ];
    }
}
