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
}