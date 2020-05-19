<?php

namespace App\Modules\Bot\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LeagueRow;
use Illuminate\Http\Request;
use App\Http\Resources\LeagueRowResource;
use App\Http\Requests\StoreLeagueRowRequest;
use App\Http\Requests\AddScoreToLeagueRowRequest;
use App\Exceptions\ValidationException;
use App\Exceptions\NotFoundException;
use App\Models\Settings;
use App\Helpers\LeagueClasses;
use App\Http\Requests\LeagueRowsListRequest;

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
        $rows = LeagueRow::query()->where('week', Settings::leagueWeek())->get();
        return LeagueRowResource::collection($rows);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMany(LeagueRowsListRequest $req)
    {
        $accounts = $req->validated()['accounts'];

        $rows = LeagueRow::query()
            ->whereIn('account_id', $accounts)
            ->where('week', Settings::leagueWeek())
            ->get();

        return LeagueRowResource::collection($rows);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeagueRowRequest $request)
    {
        $data = $request->validated();
        $data['score'] = $data['score'] ?? 0;
        $week = Settings::leagueWeek();

        if (LeagueRow::where([['account_id', '=', $data['id']]])->exists()) {
            throw new ValidationException(
                'League member with this ID already exists'
            );
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
    public function show($acc_id)
    {
        $row = LeagueRow::query()
            ->where([
                ['week', '=', Settings::leagueWeek()],
                ['account_id', '=', $acc_id],
            ])->first();

        if (is_null($row)) {
            throw new NotFoundException;
        }

        return new LeagueRowResource($row);
    }

    public function addScore(AddScoreToLeagueRowRequest $req, $acc_id)
    {
        $data = $req->validated();

        $row = LeagueRow::firstOrCreate(
            [
                'account_id' => $acc_id,
            ],
            [
                'username' => $data['username'],
                'score' => $data['score'],
                'week' => Settings::leagueWeek(),
                'class' => LeagueClasses::DEFAULT,
            ]
        );

        if (!$row->wasRecentlyCreated) {
            $row->increment('score', $data['score']);
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
