<?php

namespace App\Services;

use App\Models\OAuthAccount;
use App\Models\User;
use Carbon\Carbon;
use App\Models\UnregisteredBilling;
use Ramsey\Uuid\Uuid;

class OAuthAccountService
{
	public function setUser($acc_id, $user_id)
	{
		$updated = OAuthAccount::whereKey($acc_id)->update(['user_id' => $user_id]);

		return $updated;
	}

	public function getOrCreateMany(array $keys, int $platform)
	{
        $now = Carbon::now();
        $db_accs = OAuthAccount::whereIn('account_id', $keys)->get();

        $new_acc_keys = array_diff($keys, $db_accs->pluck('account_id')->toArray()); 	

        $new_accs = [];
        $new_billings = [];
        foreach ($new_acc_keys as $key) {
            $new_accs[] = [
                'id' => $uuid = Uuid::uuid4(),
                'account_id' => $key, 
                'oauth_provider_id' => $platform, 
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $new_billings[] = [
                'id' => Uuid::uuid4(),
                'oauth_account_id' => $uuid,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        OAuthAccount::insert($new_accs);
        UnregisteredBilling::insert($new_billings);

        $accs = OAuthAccount::whereIn('account_id', $keys)->get();
	
        return $accs;
	}
}