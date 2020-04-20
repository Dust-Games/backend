<?php

namespace App\Services;

use App\Models\User;
use App\Models\Session;
use App\Models\Billing;
use App\Services\JWT;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Api\InvalidRefreshTokenException;
use App\Contracts\JwtServiceInterface;

class UserService implements JwtServiceInterface
{
	protected const ACCESS_TOKEN_EXPIRATION = 3600; # 1 hour
	protected const REFRESH_TOKEN_EXPIRATION = 2592000; # 1 month

	protected $jwt;

	public function createUser(array $data)
	{
    	$user = User::create([
    		'username' => $data['username'],
    		'email' => $data['email'],
    		'password' => Hash::make($data['password']),
    	]);

		$user->billing()->create();

		return $user;
	}

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

	public function createAccessToken($user_id, array $claims = [])
	{
		$access_token = $this->jwt()->create(
			$user_id,
			$this->getAccessTokenTtl(),
			$claims
		);

		return $access_token;
	}

	public function createRefreshToken($user_id, array $claims = [])
	{
		$refresh_token = $this->jwt()->create(
			$user_id,
			$this->getRefreshTokenTtl(),
			$claims
		);

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

	public function getSessionByToken($refresh_token)
	{
		try {
			$refresh_token_id = $this->jwt()->parse($refresh_token)->getHeader('jti');

		} catch (\InvalidArgumentException $e) {
			
			throw new InvalidRefreshTokenException;
		}

		$session = Session::where('refresh_token_id', $refresh_token_id)->first();
        
        if (is_null($session)) {
            throw new InvalidRefreshTokenException;
        }

		return $session;
	}

	public function getAccessTokenTtl()
	{
		return static::ACCESS_TOKEN_EXPIRATION;
	}

	public function getRefreshTokenTtl()
	{
		return static::REFRESH_TOKEN_EXPIRATION;
	}

	protected function jwt()
	{
		return $this->jwt ?? $this->jwt = new JWT;
	}
}