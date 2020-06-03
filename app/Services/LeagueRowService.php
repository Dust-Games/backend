<?php

namespace App\Services;

use App\Models\LeagueRow;
use App\Models\Settings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class LeagueRowService
{
	public function getRowsByWeek($week, $per_page, $req)
	{
        $query = LeagueRow::query()
            ->where('week', $week)
            ->when($req->username, function(Builder $query, string $username) {
                $query->where('username', 'like', '%' . $username . '%');
            })
            ->orderByDesc('score')
            ->limit($per_page)
            ->select([
                'league.*',
                DB::raw('row_number() over(order by score desc) as position')
            ]);

        $rows = collect();
        for ($i=1; $i < 6; $i++) {
            $rows->put(
                $i,
                (clone $query)
                    ->where('class', $i)
                    ->paginate($per_page)
                    ->appends('by_class', $i)
            );
        }

        $keys = [];
        foreach ($rows as $class) {
            $keys = array_merge($keys, $class->pluck('account_id')->toArray());
        }

        $total_scores = LeagueRow::query()
        	->whereIn('account_id', $keys)
        	->groupBy('account_id')
        	->get(['account_id', DB::raw('sum(score) as total_score')])
        	->keyBy('account_id');

        foreach ($rows as $class) {
            $class = $class->map(function ($row) use ($total_scores) {
                $row->setAttribute(
                    'total_score',
                    $total_scores[$row->getAccountKey()]->total_score
                );
            });
        }

        return $rows;
	}

    public function getRowsByClass($class, $per_page, $week = null, $query = null)
    {
        $week = $week ?? Settings::leagueWeek();

        $query = $query ?? LeagueRow::query();

        $query->where([
            ['class', '=', $class],
            ['week', '=', $week],
        ]);

        if (empty($query->orders)) {
            $query->orderByDesc('score');
        }

        $rows = $query
            ->select([
                'league.*',
                DB::raw('row_number() over(order by score desc) as position')
            ])
            ->paginate($per_page);

        $keys = $rows->pluck('account_id')->toArray();

        $total_scores = LeagueRow::query()
            ->whereIn('account_id', $keys)
            ->groupBy('account_id')
            ->get(['account_id', DB::raw('sum(score) as total_score')])
            ->keyBy('account_id');

        $rows->map(function ($row) use ($total_scores) {
            return $row->setAttribute(
                'total_score',
                $total_scores[$row->getAccountKey()]->total_score
            );
        });

        return $rows;
    }

    /**
     *
     * @return string
     */
    public function createNextWeek()
    {
        $currentWeek = Settings::leagueWeek();
        if ($currentWeek === Settings::maxLeagueWeeks()) return 'Идет последняя неделя';
        $nextWeek = LeagueRow::query()->max('week');
        if ($nextWeek > $currentWeek) return 'Вы уже создали следующуюю неделю';
        $nextWeek = $currentWeek + 1;
        $leagues = LeagueRow::query()
            ->select('account_id', DB::raw('max(class) as class, max(username) as username'))
            ->groupBy('account_id')
            ->get();
        foreach ($leagues as $league)
            LeagueRow::query()
                ->create([
                    'account_id' => $league->account_id,
                    'username' => $league->username,
                    'class' => $league->class,
                    'week' => $nextWeek,
                    'score' => 0
                ]);
        return 'Создана неделя № ' . $nextWeek;
    }
}
