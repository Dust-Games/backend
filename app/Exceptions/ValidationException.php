<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends ApiException
{
	public function getDefaultCode()
	{
		return 422;
	}

	public function getDefaultMessage()
	{
		return 'The given data is invalid.';
	}
}
