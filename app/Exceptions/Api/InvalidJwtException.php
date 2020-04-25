<?php

namespace App\Exceptions\Api;

use Exception;

class InvalidJwtException extends ValidationException
{
	public function getDefaultErrors($errors)
	{
		return [
			'token' => trans('validation.jwt'),
		];
	}
}
