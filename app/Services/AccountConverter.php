<?php

namespace App\Services;

use Laravel\Socialite\AbstractUser;
use App\Models\OAuthAccount;
use App\Models\OAuthProvider;

/**
 * Class for converting OAuth users to our users
 **/
class AccountConverter
{
	public function steam(AbstractUser $user)
	{
		$oauth_data = $user->getRaw();

		$data = [
			'oauth_provider_id' => OAuthProvider::PROVIDERS[__FUNCTION__]['id'],
			'account_id' => $oauth_data['steamid'],
			'username' => $oauth_data['personaname'],
		];

		$account = (new OAuthAccount)->fill($data);

		return $account;
	}

	public function twitch(AbstractUser $user)
	{
		$oauth_data = $user->getRaw();

		$data = [
			'oauth_provider_id' => OAuthProvider::PROVIDERS[__FUNCTION__]['id'],
			'account_id' => $oauth_data['id'],
			'username' => $oauth_data['display_name'],
		];

		$account = (new OAuthAccount)->fill($data);

		return $account;
	}

	public function discord(AbstractUser $user)
	{
		$oauth_data = $user->getRaw();

		$data = [
			'oauth_provider_id' => OAuthProvider::PROVIDERS[__FUNCTION__]['id'],
			'account_id' => $oauth_data['id'],
			'username' => $oauth_data['username'],
		];

		$account = (new OAuthAccount)->fill($data);

		return $account;		
	}

	protected function setAccountFields($account_id, $username)
	{
		
	}
}