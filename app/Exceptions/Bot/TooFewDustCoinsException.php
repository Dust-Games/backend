<?php

namespace App\Exceptions\Bot;

use Exception;

class TooFewDustCoinsException extends Exception
{
    public function render()
    {
    	return response()->json([
    		'message' => 'The given data was invalid',
    		'error' => 'Too few dust coins on account wallet.'
    	], 422);
    }
}
