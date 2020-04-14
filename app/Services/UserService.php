<?php

namespace App\Services;

use App\Models\User;
use App\Models\Session;
use App\Services\JWT;
use Ramsey\Uuid\Uuid;

class UserService
{
	protected const ACCESS_TOKEN_EXPIRATION = 3600; # 1 hour
	protected const REFRESH_TOKEN_EXPIRATION = 2592000; # 1 month

	protected $jwt;

	public function createTokens($user_id, array $session_fields = [])
	{
		$access_token = $this->createAccessToken($user_id);
		$refresh_token = $this->createRefreshToken($user_id);

		$this->createSession(
			$user_id,
			$refresh_token->getHeader('jti'),
			$refresh_token->getClaim('exp'),
			$session_fields
		);

		return [
			'access_token' => (string) $access_token,
			'refresh_token' => (string) $refresh_token,
		];
		
	}

	public function createAccessToken($user_id)
	{
		$access_token = $this->jwt()->create($user_id, static::ACCESS_TOKEN_EXPIRATION);

		return $access_token;
	}

	public function createRefreshToken($user_id)
	{
		$refresh_token = $this->jwt()->create($user_id, static::REFRESH_TOKEN_EXPIRATION);

		return $refresh_token;
	}

	public function createSession($user_id, $refresh_token_id, $expires_at, array $session_fields = [])
	{
		$fields = array_merge([
			'user_id' => $user_id,
			'refresh_token_id' => $refresh_token_id,
			'expires_at' => $expires_at,
		], $session_fields);

		$session = Session::create($fields);

		return $session;
	}

	protected function jwt()
	{
		return $this->jwt ?? $this->jwt = new JWT;
	}
}