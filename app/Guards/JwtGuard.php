<?php

namespace App\Guards;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\UserProvider;
use App\Services\JWT;
use Illuminate\Http\Request;
use App\Exceptions\Api\AuthenticationException;

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
			if ($token = $this->getTokenFromRequest()) {
				$token = $this->jwt->parse($token);

				if ($this->jwt->verify($token) && $this->jwt->validate($token)) {
					return $this->user = $this->provider->retrieveById($this->jwt->getOwnerKey($token));
				}
			}

			return;

		} catch (\Exception $e) {
			return;
		}
	}	

    /**
     * Determine if current user is authenticated. If not, throw an exception.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     *
     * @throws \App\Exceptions\Api\AuthenticationException
     */
    public function authenticate()
    {
        if (! is_null($user = $this->user())) {
            return $user;
        }

        throw new AuthenticationException('This action is unathorized.');
    }

	public function getTokenFromRequest()
	{
		return $this->request->bearerToken() ?? false;
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