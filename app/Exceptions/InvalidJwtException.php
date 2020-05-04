<?php

namespace App\Exceptions;

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
