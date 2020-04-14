<?php

namespace App\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\UserProvider;
use App\Services\JWT;
use Illuminate\Http\Request;

class JwtGuard implements Guard
{
	use GuardHelpers;

	protected $request;
	protected $jwt;

	public function __construct(UserProvider $provider, Request $request)
	{
		$this->provider = $provider;
		$this->request = $request;

		$this->jwt = new JWT;
	}

	public function user()
	{
		if (!is_null($this->user)) {
			return $this->user;
		}

		try {
			$token = $this->jwt->parse($this->getTokenFromRequest());

			if ($this->jwt->verify($token) && $this->jwt->validate($token)) {
				return $this->user = $this->provider->retrieveById($token->getClaim('sub'));
			}

			return;

		} catch (\Exception $e) {
			return;
		}
	}	

	public function getTokenFromRequest()
	{
		return $this->request->bearerToken() ?? '';
	}

    public function validate(array $credentials = [])
    {
        if (empty($credentials['id'])) {
            return false;
        }

        if ($this->provider->retrieveById($credentials['id'])) {
            return true;
        }

        return false;
    }
}