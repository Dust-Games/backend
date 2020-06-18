<?php

namespace App\Models;

use App\Concerns\HasUuidPrimaryKey;
use App\Exceptions\TooFewCurrencyException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdminCurrencyAccountChange extends Model
{
    use HasUuidPrimaryKey;

    public $fillable = ['type', 'way'];

    /* Helpers */

    /**
     * @param Model $account
     * @param float $amount
     * @param string $way
     * @return mixed
     */
    public static function createSetBalance(Model $account, float $amount, string $way = 'bot')
    {
        return DB::transaction(function () use ($account, $amount, $way) {
            $type = 'set';
            $change = self::query()->create(compact('type', 'way'));
            CashFlow::query()->create([
                'debt_id' => $account->id,
                'amount' => $account->balance,
                'operation_id' => $change->id,
                'operation_type' => self::class
            ]);
            CashFlow::query()->create([
                'credit_id' => $account->id,
                'amount' => $amount,
                'operation_id' => $change->id,
                'operation_type' => self::class
            ]);
            $account->balance = Round($amount, 3);
            $account->save();
            return $account;
        });
    }

    /**
     * @param Model $account
     * @param float $amount
     * @param string $way
     * @return mixed
     */
    public static function createIncBalance(Model $account, float $amount, string $way = 'bot')
    {
        return DB::transaction(function () use ($account, $amount, $way) {
            $type = 'add';
            $change = self::query()->create(compact('type', 'way'));
            CashFlow::query()->create([
                'credit_id' => $account->id,
                'amount' => $amount,
                'operation_id' => $change->id,
                'operation_type' => self::class
            ]);
            $account->balance = Round($account->balance + $amount, 3);
            $account->save();
            return $account;
        });
    }

    /**
     * @param CurrencyAccount $account
     * @param float $amount
     * @param string $way
     * @return mixed
     * @throws \Throwable
     */
    public static function createDecBalance(CurrencyAccount $account, float $amount, string $way = 'bot')
    {
        throw_if($account->balance < $amount, new TooFewCurrencyException($account));
        return DB::transaction(function () use ($account, $amount, $way) {
            $type = 'reduce';
            $change = self::query()->create(compact('type', 'way'));
            CashFlow::query()->create([
                'debt_id' => $account->id,
                'amount' => $amount,
                'operation_id' => $change->id,
                'operation_type' => self::class
            ]);
            $account->balance = Round($account->balance - $amount, 3);
            $account->save();
            return $account;
        });
    }

    /**
     * @param array $currencyAccounts
     * @param float $amount
     * @param string $way
     * @return mixed
     */
    public static function createIncBalanceToSeveral(array $currencyAccounts, float $amount, $way = 'bot')
    {
        return DB::transaction(function () use ($currencyAccounts, $amount, $way) {
            $type = 'add';
            $change = self::query()->create(compact('type', 'way'));
            $ret = collect();
            foreach ($currencyAccounts as $account) {
                CashFlow::query()->create([
                    'credit_id' => $account->id,
                    'amount' => $amount,
                    'operation_id' => $change->id,
                    'operation_type' => self::class
                ]);
                $account->balance = Round($account->balance + $amount, 3);
                $account->save();
                $ret->push($account);
            }
            return $ret;
        });
    }

    /* Relations */

    public function cashFlows()
    {
        return $this->morphMany(CashFlow::class, 'owner');
    }
}
