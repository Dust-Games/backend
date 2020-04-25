<?php

namespace App\Exceptions\Api;

use Exception;

class NotFoundException extends ApiException
{
	public function getDefaultCode()
	{
		return 404;
	}

	public function getDefaultMessage()
	{
		return 'Not found.';
	}
}
