<?php

namespace App\Exceptions\Api;

use Exception;

class InvalidRefreshTokenException extends Exception
{
	public function render()
	{
		return response([
			'message' => 'Invalid refresh token.',
			'errors' => [
				'refresh_token' => trans('validation.refresh_token')
			]
		], 422);
	}
}
