<?php


namespace App\Modules\Bot\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\OAuthAccount;
use App\Modules\Bot\Http\Requests\DuelRequest;

class DuelController extends Controller
{
    public function update(DuelRequest $request, OAuthAccount $account)
    {
        $account->total_bets += $request->bet;
        $account->number_of_games += 1;
        $account->number_of_wins += $request->win ? 1 : 0;
        $rules = config('duel_rules');
        for ($i = 0; $i < count($rules); $i++) {
            if ($account->number_of_wins >= $rules[$i]['wins'] && $account->total_bets >= $rules[$i]['coins']) {
                $account->duel_rating = $i +1;
            }
        }
        $account->save();
        return $account;
    }
}
