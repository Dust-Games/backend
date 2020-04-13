<?php

namespace App\Services;

use App\Contracts\JwtInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Ramsey\Uuid\Uuid;

class JWT implements JwtInterface
{
	protected const EXPIRES_IN = 3600;
	
	protected $signer_alg = Sha256::class;

	protected $builder;

	public function __construct()
	{
		$this->builder = $this->initBuilder();
	}

	public function create($user_id, $expires_in = self::EXPIRES_IN)
	{
		$signer = new $this->signer_alg;
		$private_key = new Key(config('jwt.private_key'));
		$time = time();

		$token = $this->builder
			->issuedBy(config('app.url'))
			->permittedFor(config('app.url'))
			->identifiedBy($this->generateUuid(), true)
			->issuedAt($time)
			->canOnlyBeUsedAfter($time)
			->expiresAt($time + $expires_in)
			->withClaim('uid', $user_id)
			->getToken($signer, $private_key);
	
		return $token;
	}

	public function parse(string $token)
	{
		$token = (new Parser())->parse($token);

		return $token;
	}

	public function verify(Token $token)
	{
		return $token->verify(new $this->signer_alg, config('jwt.public_key'));
	}

	public function verifyRaw(string $token)
	{
		return $this->verify($this->parse($token));
	}

	protected function generateUuid()
	{
		return Uuid::uuid4()->toString();
	}

	protected function initBuilder()
	{
		$builder = new Builder;

		return $builder;
	}

	public static function __callStatic($name, $arguments)
	{
		return (new static)->{$name}($arguments);
	}
}