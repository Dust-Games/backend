<?php

namespace App\Services;

use App\Models\Billing;
use App\Models\UnregisteredBilling;
use App\Models\OAuthAccount;
use Illuminate\Support\Collection;

class BillingService
{
	public function getByAccount(OAuthAccount $acc)
	{
    	$billing = $acc->hasUser() ?
    		Billing::whereUser($acc->getUserKey())->first()
    		:
    		UnregisteredBilling::whereAccount($acc->getKey())->first();

    	if (is_null($billing)) {
    		return $this->createForAccount($acc);
    	}
    	
    	return $billing;		
	}

	public function createForAccount(OAuthAccount $acc)
	{
		$billing = UnregisteredBilling::create([
			'oauth_account_id' => $acc->getKey(),
		]);

		return $billing;
	}

	public function addDustCoinsToMany(Collection $accounts, $sum)
	{
		$unreg_acc_keys = $accounts->where('user_id', null)->pluck('id')->toArray();

		UnregisteredBilling::whereIn('oauth_account_id', $unreg_acc_keys)->increment(
			'dust_coins_num',
			$sum,
		);

		$users_leys = $accounts->filter(function ($acc) { 
			return $acc->getUserKey() !== null;
		})->pluck('user_id')->toArray();

		Billing::whereIn('user_id', $users_leys)->increment(
			'dust_coins_num',
			$sum
		);
	}
}