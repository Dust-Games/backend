<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
	protected $errors;

	public function __construct(
		$errors = null,
		string $message = null,
		int $code = null,
		Throwable $previous = null
	)
	{
		$this->errors = is_array($errors) ? $errors : $this->getDefaultErrors($errors);

		parent::__construct(
			$message ?? $this->getDefaultMessage(), 
			$code ?? $this->getDefaultCode(), 
			$previous
		);
	}

	public function render()
	{
		return response([
			'message' => $this->getMessage(),
			'errors' => $this->errors,
		], $this->getCode());
	}

	public function getDefaultErrors($error)
	{
		return ['_other' => $error ?? $this->getDefaultMessage()];
	}

	public function getDefaultCode()
	{
		return 400;
	}

	public function getDefaultMessage()
	{
		return 'Invalid request.';
	}
}
