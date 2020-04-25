<?php

namespace App\Exceptions\Api;

use Exception;

class AuthenticationException extends ApiException
{
	public function getDefaultCode()
	{
		return 401;
	}

	public function getDefaultMessage()
	{
		return 'This action is unathorized.';
	}
}
