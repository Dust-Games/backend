<?php

namespace App\Exceptions;

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
