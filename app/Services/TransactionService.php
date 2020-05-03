<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
class TransactionService
{
	public const DUST_COIN_TYPE = 0;

	public function createForDustCoins(
		$coins_num,
		string $owner_id, 
		int $action,
		bool $is_registered = true
	)
	{
		Transaction::create([
			'currency_num' => $coins_num,
			'owner_id' => $owner_id,
			'action' => $is_registered ? $action + 10 : $action,
			'is_registered' => $is_registered,
			'currency_type' => static::DUST_COIN_TYPE,
		]);
	}

	public function createManyForDustCoins(
		$coins_num,
		Collection $owners,
		int $action,
		bool $is_registered = true
	)
	{
		$now = Carbon::now();
		$transactions = [];
		foreach ($owners as $owner) {
			$transactions[] = [
				'id' => Uuid::uuid4(),
				'currency_num' => $coins_num,
				'owner_id' => $owner->getKey(),
				'action' => $action,
				'is_registered' => $is_registered,
				'currency_type' => static::DUST_COIN_TYPE,
				'created_at' => $now,
				'updated_at' => $now,
			];
		}

		Transaction::insert($transactions);
	}
}