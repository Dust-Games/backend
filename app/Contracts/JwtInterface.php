<?php

namespace App\Contracts;

use Lcobucci\JWT\Token;

interface JwtInterface 
{
	public function create($user_id);

	public function parse(string $token);

	public function verify(Token $token);
	
	public function verifyRaw(string $token);
}