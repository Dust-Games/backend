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

    	return $billing;		
	}

	public function createForAccount(OAuthAccount $acc)
	{
		$billing = UnregisteredBilling::create([
			'oauth_account_id' => $acc->getKey(),
		]);

		return $billing;
	}

	public function addDustCoinsToMany(Collection $accounts)
	{
		$unreg_acc_keys = $accounts->where('user_id', null)->pluck('id')->toArray();

		$unreg_billings = UnregisteredBilling::whereIn($unreg_acc_keys)->get();

		$db_acc_keys = $unreg_billings->pluck('oauth_account_id')->toArray();

		$new_keys = $un;
	}
}