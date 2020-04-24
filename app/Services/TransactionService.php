<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
	public const DUST_COIN_TYPE = 0;

	public function createForDustCoins(
		int $coins_num,
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
}