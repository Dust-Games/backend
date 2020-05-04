<?php

namespace App\Modules\Bot\Exceptions;

use Exception;
use Throwable;

class TooFewDustCoinsException extends Exception
{
	protected $dust_coins_num;

	public function __construct(
		$dust_coins_num,
		string $message = "asd",
		int $code = 0, 
		Throwable $previous = null
	)
	{
		$this->dust_coins_num = $dust_coins_num;

		parent::__construct($message, $code, $previous);
	}

    public function render($request)
    {
    	return response()->json([
    		'message' => 'The given data was invalid',
    		'errors' => ['_other' => 'Too few dust coins on account wallet.'],
    		'dust_coins_num' => $this->getDustCoins(),
    	], 422);
    }

	public function getDustCoins()	
	{
		return $this->dust_coins_num;
	}

}
