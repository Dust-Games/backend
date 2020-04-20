<?php

namespace App\Contracts;

interface JwtServiceInterface 
{
	public function createAccessToken($entity_pk, array $claims = []);
	public function createRefreshToken($entity_pk, array $claims = []);

	public function getAccessTokenTtl();
	public function getRefreshTokenTtl();
}