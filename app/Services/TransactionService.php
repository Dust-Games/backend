<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
	public const DUST_TOKEN_TYPE = 0;

	public function createForDustTokens(
		int $tokens_num,
		string $owner_id, 
		int $action,
		bool $is_registered = true
	)
	{
		Transaction::create([
			'tokens_num' => $tokens_num,
			'owner_id' => $owner_id,
			'action' => $is_registered ? $action + 10 : $action,
			'is_registered' => $is_registered,
			'token_type' => static::DUST_TOKEN_TYPE,
		]);
	}
}