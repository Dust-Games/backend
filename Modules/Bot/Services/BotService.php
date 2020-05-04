<?php

namespace App\Modules\Bot\Services;

use App\Contracts\JwtServiceInterface;
use App\Services\JWT;
use Ramsey\Uuid\Uuid;

class BotService implements JwtServiceInterface
{
	protected const ACCESS_TOKEN_TTL = 3600 * 24; # 1 day
	protected const REFRESH_TOKEN_TTL = 2592000; # 1 month

	public function createTokens($bot_id)
	{
		return [
			'access_token' => (string) $this->createAccessToken($bot_id),
			'refresh_token' => (string) $this->createRefreshToken($bot_id),
		];
	}

	public function createAccessToken($bot_id, array $claims = [])
	{
		$access_token = $this->jwt()->create(
			$bot_id,
			$this->getAccessTokenTtl(),
			$claims
		);

		return $access_token;
	}

	public function createRefreshToken($bot_id, array $claims = [])
	{
		$refresh_token = $this->jwt()->create(
			$bot_id,
			$this->getRefreshTokenTtl(),
			$claims
		);

		return $refresh_token;
	}

	public function getAccessTokenTtl()
	{
		return static::ACCESS_TOKEN_TTL;
	}

	public function getRefreshTokenTtl()
	{
		return static::REFRESH_TOKEN_TTL;
	}

	protected function jwt()
	{
		return $this->jwt ?? $this->jwt = new JWT;
	}
}