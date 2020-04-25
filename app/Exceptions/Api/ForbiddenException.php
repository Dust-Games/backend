<?php

namespace App\Exceptions\Api;

use Exception;

class ForbiddenException extends ApiException
{
	public function getDefaultCode()
	{
		return 403;
	}

	public function getDefaultMessage()
	{
		return 'Доступ запрещен.';
	}
}
