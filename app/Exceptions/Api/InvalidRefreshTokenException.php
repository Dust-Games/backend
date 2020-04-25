<?php

namespace App\Exceptions\Api;

use Exception;

class InvalidRefreshTokenException extends ValidationException
{
	public function getDefaultErrors($errors)
	{
		return [
			'refresh_token' => trans('validation.refresh_token'),
		];
	}
}
