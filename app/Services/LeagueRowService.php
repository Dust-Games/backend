<?php

namespace App\Services;

use App\Models\LeagueRow;
use DB;

class LeagueRowService
{
	public function getRowsByWeek($week, $per_page)
	{
        $rows = LeagueRow::
            where('week', $week)
            ->orderByDesc('score')->paginate($per_page, [
                'league.*', 
                DB::raw('row_number() over(order by score desc) as position')
        ]);

        $keys = $rows->pluck('account_id')->toArray();

        $total_scores = LeagueRow::
        	whereIn('account_id', $keys)
        	->groupBy('account_id')
        	->get(['account_id', DB::raw('sum(score) as total_score')])
        	->keyBy('account_id');

        $rows->map(function($row) use($total_scores) {
        	return $row->setAttribute(
        		'total_score', 
        		$total_scores[$row->getAccountKey()]->total_score
        	);
        });

        return $rows;
	}
}
