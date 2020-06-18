<?php

namespace App\Services;

use App\Models\OAuthAccount;
use Carbon\Carbon;
use App\Models\UnregisteredBilling;
use Ramsey\Uuid\Uuid;

class OAuthAccountService
{
	public function setUser($acc_id, $user_id)
	{
		$updated = OAuthAccount::query()->whereKey($acc_id)->update(['user_id' => $user_id]);

		return $updated;
	}

    /**
     * @param array $accountIds
     * @param int $platform
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
	public function getOrCreate(array $accountIds, int $platform)
    {
        $existingAccounts = OAuthAccount::query()->whereIn('account_id', $accountIds)->get();
        $existingAccountIds = $existingAccounts->map(function ($existingAccount) {
            return $existingAccount->account_id;
        })->toArray();
        if (count($accountIds) === count($existingAccountIds))
            return $existingAccounts;
        $notExistAccountIds = array_diff($accountIds, $existingAccountIds);
        foreach ($notExistAccountIds as $notExistAccountId) {
            OAuthAccount::query()->create([
                'account_id' => $notExistAccountId,
                'oauth_provider_id' => $platform,
            ]);
        };
        return OAuthAccount::query()->whereIn('account_id', $accountIds)->get();
    }

	public function getOrCreateMany(array $keys, int $platform)
	{
        $now = Carbon::now();
        $db_accs = OAuthAccount::query()->whereIn('account_id', $keys)->get();

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

        OAuthAccount::query()->insert($new_accs);
        UnregisteredBilling::query()->insert($new_billings);

        $accs = OAuthAccount::query()->whereIn('account_id', $keys)->get();

        return $accs;
	}
}
