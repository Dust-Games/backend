<?php

namespace App\Exceptions\Api;

use Exception;

class InvalidJwtException extends Exception
{
	public function render()
	{
		return response([
			'message' => 'Invalid JWT token.',
		], 422);
	}
}
