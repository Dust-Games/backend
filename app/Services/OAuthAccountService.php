<?php

namespace App\Services;

use App\Models\OAuthAccount;
use App\Models\User;

class OAuthAccountService
{
	public function setUser($acc_id, $user_id)
	{
		$updated = OAuthAccount::whereKey($acc_id)->update(['user_id' => $user_id]);

		return $updated;
	}

	public function getOrCreateMany(array $keys, int $platform)
	{
        $db_accs = OAuthAccount::whereIn('account_id', $keys)->get();

        $new_acc_ids = array_diff($data['accounts'], $db_accs->pluck('account_id')->toArray()); 	

        $new_acc_rows = array_map(function ($item) use ($platform) {
            return [
            	'account_id' => $item,
            	'oauth_provider_id' => $platform
			];
        }, $new_acc_ids);

        $new_accs = [];
        $new_billings = [];
        array_walk($new_acc_ids, function ($key) use ($new_billings, $new_accs, $platform) {
        	$new_accs[] = ['account_id' => $key, 'oauth_provider_id' => $platform, ];
            $new_billings[] = ['oauth_account_id' => $key];

        });

        OAuthAccount::insert($new_acc_rows);
        UnregisteredBilling::insert()

        $accs = OAuthAccount::whereIn('account_id', $keys)->get();
	
        return $accs;
	}
}