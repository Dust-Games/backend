<?php

namespace App\Exceptions\Api;

use Exception;

class ValidationException extends Exception
{
	protected $dust_coins_num;

	public function __construct(
		$error,
		string $message = "",
		int $code = 0, 
		Throwable $previous = null
	)
	{
		$this->error;

		parent::__construct($message, $code, $previous);
	}
	public function render()
	{
		return response([
			'The given data was invalid.',
			'errors' => [
				$this->name ?? 'other' => $this->error,
			]
		], 422);
	}
}
