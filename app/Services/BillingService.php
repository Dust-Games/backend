<?php

namespace App\Services;

use App\Models\Billing;
use App\Models\UnregisteredBilling;
use App\Models\OAuthAccount;

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

	public function getOrCreateMany(array $accounts)
	{
		$unreg_accs = $accounts->where('user_id', null);
	}
}