<?php

namespace App\Models;

use App\Concerns\HasUuidPrimaryKey;
use App\Exceptions\TooFewCurrencyException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class OrderChange extends Model
{
    use HasUuidPrimaryKey;

    public $fillable = ['type', 'way'];

    /* Helpers */

    /**
     * @param CurrencyAccount $parentalCurrencyAccount
     * @param $parameters
     * @return mixed
     * @throws \Throwable
     */
    public static function createOrder(CurrencyAccount $parentalCurrencyAccount, $parameters)
    {
        throw_if(
            $parentalCurrencyAccount->balance < $parameters['amount'],
            new TooFewCurrencyException($parentalCurrencyAccount)
        );
        return DB::transaction(function() use ($parentalCurrencyAccount, $parameters) {

            $order = Order::query()->create([
                'currency_account_id' => $parentalCurrencyAccount->id,
                'exchange_rate' => $parameters['exchange_rate'],
                'closed' => false,
            ]);

            $order->currencyAccount()->create([
                'currency_id' => $parentalCurrencyAccount->currency_id,
                'balance' => $parameters['amount'],
                'closed' => false,
                'id' => Uuid::uuid4(),
            ]);

            $parentalCurrencyAccount->decrement('balance', $parameters['amount']);

            $change = self::query()->create(['type' => 'create']);

            CashFlow::query()->create([
                'debt_id' => $parentalCurrencyAccount->id,
                'credit_id' => $order->currencyAccount->id,
                'amount' => $parameters['amount'],
                'operation_id' => $change->id,
                'operation_type' => self::class,
            ]);

            return $order;
        });
    }

    /**
     * @param Order $order
     * @return mixed
     */
    public static function closeOrder(Order $order)
    {
        return DB::transaction(function() use ($order) {

            $order->parentalCurrencyAccount->increment('balance', $order->currencyAccount->balance);

            $order->update(['closed' => true]);

            $change = self::query()->create(['type' => 'close']);

            CashFlow::query()->create([
                'debt_id' => $order->currencyAccount->id,
                'credit_id' => $order->parentalCurrencyAccount->id,
                'amount' => $order->currencyAccount->balance,
                'operation_id' => $change->id,
                'operation_type' => self::class,
            ]);

            $order->currencyAccount->update([
                'balance' => 0,
                'closed' => true,
            ]);

            return $order;
        });
    }

    /**
     * @param Order $order
     * @param $parameters
     * @return mixed
     * @throws \Throwable
     */
    public static function credit(Order $order, $parameters)
    {
        throw_if(
            $order->parentalCurrencyAccount->balance < $parameters['amount'],
            new TooFewCurrencyException($order->parentalCurrencyAccount)
        );
        return DB::transaction(function() use ($order, $parameters) {

            $order->parentalCurrencyAccount->decrement('balance', $parameters['amount']);

            $order->currencyAccount->increment('balance', $parameters['amount']);

            $change = self::query()->create(['type' => 'credit']);

            CashFlow::query()->create([
                'debt_id' => $order->parentalCurrencyAccount->id,
                'credit_id' => $order->currencyAccount->id,
                'amount' => $parameters['amount'],
                'operation_id' => $change->id,
                'operation_type' => self::class,
            ]);

            return $order;
        });
    }

    /**
     * @param Order $order
     * @param $parameters
     * @return mixed
     * @throws \Throwable
     */
    public static function debit(Order $order, $parameters)
    {
        throw_if(
            $order->currencyAccount->balance < $parameters['amount'],
            new TooFewCurrencyException($order->currencyAccount)
        );
        return DB::transaction(function() use ($order, $parameters) {

            $order->parentalCurrencyAccount->increment('balance', $parameters['amount']);

            $order->currencyAccount->decrement('balance', $parameters['amount']);

            $change = self::query()->create(['type' => 'debit']);

            CashFlow::query()->create([
                'credit_id' => $order->parentalCurrencyAccount->id,
                'debt_id' => $order->currencyAccount->id,
                'amount' => $parameters['amount'],
                'operation_id' => $change->id,
                'operation_type' => self::class,
            ]);

            return $order;
        });
    }

    public static function exchange(Order $order, User $user, $parameters)
    {
        throw_if(
            $order->currencyAccount->balance < $parameters['amount'],
            new TooFewCurrencyException($order->currencyAccount)
        );
        $usdt = Currency::query()->where('alias', 'USDT')->first();
        $debtUsdt = $user
            ->currencyAccounts()
            ->firstOrCreate(
                [
                    'closed' => 'false',
                    'currency_id' => $usdt->id
                ],
                [
                    'id' => Uuid::uuid4(),
                    'balance' => 0,
                ]
            );
        $amountUsdt = Round($parameters['amount'] * $order->exchange_rate, 3);
        throw_if($debtUsdt->balance < $amountUsdt, new TooFewCurrencyException($debtUsdt));
        $creditUsdt = $order
            ->parentalCurrencyAccount
            ->owner
            ->currencyAccounts()
            ->firstOrCreate(
                [
                    'closed' => 'false',
                    'currency_id' => $usdt->id
                ],
                [
                    'id' => Uuid::uuid4(),
                    'balance' => 0,
                ]
            );
        $creditDc = $user
            ->currencyAccounts()
            ->firstOrCreate(
                [
                    'closed' => 'false',
                    'currency_id' => $order->currencyAccount->currency_id
                ],
                [
                    'id' => Uuid::uuid4(),
                    'balance' => 0,
                ]
            );
        return DB::transaction(function () use ($order, $creditUsdt, $creditDc, $debtUsdt, $amountUsdt, $parameters) {
            $change = self::query()->create(['type' => 'exchange']);
            $order->currencyAccount->decrement('balance', $parameters['amount']);
            $creditDc->increment('balance', $parameters['amount']);
            CashFlow::query()->create([
                'credit_id' => $creditDc->id,
                'debt_id' => $order->currencyAccount->id,
                'amount' => $parameters['amount'],
                'operation_id' => $change->id,
                'operation_type' => self::class,
            ]);
            $commission = $order->parentalCurrencyAccount->owner->role()->first()->commission;
            $comissionAmount = Round($amountUsdt * $commission / 100, 3);
            $mainAmount = Round($amountUsdt - $comissionAmount, 3);
            $debtUsdt->decrement('balance', $mainAmount);
            $creditUsdt->increment('balance', $mainAmount);
            CashFlow::query()->create([
                'credit_id' => $creditUsdt->id,
                'debt_id' => $debtUsdt->id,
                'amount' => $mainAmount,
                'operation_id' => $change->id,
                'operation_type' => self::class,
            ]);
            $debtUsdt->decrement('balance', $comissionAmount);
            CashFlow::query()->create([
                'debt_id' => $debtUsdt->id,
                'amount' => $comissionAmount,
                'operation_id' => $change->id,
                'operation_type' => self::class,
            ]);
            return $order;
        });
    }

    /* Relations */

    public function cashFlows()
    {
        return $this->morphMany(CashFlow::class, 'operation');
    }
}
