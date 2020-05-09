<?php

namespace App\Modules\Bot\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LeagueRow;
use Illuminate\Http\Request;
use App\Http\Resources\LeagueRowResource;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreLeagueRowRequest;
use App\Http\Requests\UpdateLeagueRowRequest;
use App\Exceptions\ValidationException;
use App\Exceptions\NotFoundException;

class LeagueRowController extends Controller
{
    private const PER_PAGE = 20;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function weekList($week)
    {
        $rows = LeagueRow::
            where('week', $week)
            ->orderByDesc('score')->paginate(static::PER_PAGE, [
                'league.*', 
                DB::raw('row_number() over(order by score desc) as position')
        ]);

        return LeagueRowResource::collection($rows);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(int $week, StoreLeagueRowRequest $request)
    {
        $data = $request->validated();
        $data['score'] = $data['score'] ?? 0;

        if (LeagueRow::where('account_id', $data['id'])->where('week', $week)->exists()) {
            throw new ValidationException('League member with this ID and week already exists');
        }

        $row = LeagueRow::create([
            'account_id' => $data['id'],
            'username' => $data['username'],
            'week' => $week,
            'class' => $data['class'],
            'score' => $data['score'],
        ]);

        return $row;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LeagueRow  $leagueRow
     * @return \Illuminate\Http\Response
     */
    public function show(int $week, $row_key)
    {
        $row = LeagueRow::where('account_id', '=', $row_key)->first();

        if (is_null($row)) {
            throw new NotFoundException;
        }

        return new LeagueRowResource($row);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeagueRow  $leagueRow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeagueRow $leagueRow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeagueRow  $leagueRow
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeagueRow $leagueRow)
    {
        //
    }
}
