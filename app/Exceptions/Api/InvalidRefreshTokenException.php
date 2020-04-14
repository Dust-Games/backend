<?php

namespace App\Exceptions\Api;

use Exception;

class InvalidRefreshTokenException extends Exception
{
	public function render()
	{
		return response([
			'message' => 'Invalid refresh token.',
		], 422);
	}
}
