<?php

namespace App\Exceptions;

use App\Models\CurrencyAccount;
use Exception;
use Throwable;

class TooFewCurrencyException extends Exception
{
    protected $account;

    public function __construct(
        CurrencyAccount $account,
        string $message = "Too few currency on account",
        int $code = 0,
        Throwable $previous = null
    )
    {
        $this->account = $account;
        parent::__construct($message, $code, $previous);
    }

    public function render()
    {
        $currency = $this->account->currency->name;
        return response()->json([
            'message' => 'The given data was invalid',
            'errors' => ['_other' => 'Too few ' . $currency . ' on account wallet.'],
            'balance' => $this->account->balance,
        ], 422);
    }
}
