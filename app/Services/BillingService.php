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
}