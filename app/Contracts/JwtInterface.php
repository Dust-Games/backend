<?php

namespace App\Contracts;

use Lcobucci\JWT\Token;

interface JwtInterface 
{
	public function create($user_id, int $expires_in, array $claims = []);

	public function parse(string $token);

	public function verify(Token $token);
	
	public function verifyRaw(string $token);

	public function getOwnerKey(Token $token);

	public function getOwnerKeyByRaw(string $token);
}