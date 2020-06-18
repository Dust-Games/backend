<?php


namespace App\Services;


use App\Models\Currency;
use App\Models\CurrencyAccount;
use App\Models\OAuthAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class CurrencyAccountService
{
    /**
     * @param User $user
     * @param int $currencyId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function userAccount(User $user, int $currencyId)
    {
        return CurrencyAccount::query()
            ->firstOrCreate([
                'currency_id' => $currencyId,
                'owner_id' => $user->id,
                'owner_type' => User::class,
            ]);
    }

    /**
     * @param OAuthAccount $acc
     * @param int $currencyId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    private function oauthAccount(OAuthAccount $acc, int $currencyId)
    {
        return CurrencyAccount::query()
            ->firstOrCreate(
                [
                    'currency_id' => $currencyId,
                    'owner_id' => $acc->id,
                    'owner_type' => OAuthAccount::class,
                ],
                [
                    'id'  => Uuid::uuid4(),
                    'balance' => 0,
                    'closed' => false,
                ]
            );
    }

    /**
     * @param Model $acc
     * @param int|null $currencyId
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function getByOauthAccount(OAuthAccount $acc, int $currencyId = null)
    {
        $currency = Currency::query()
            ->where('id', $currencyId)
            ->firstOr(function() {
                return Currency::query()
                    ->where('alias', config('app.default_currency', 'DC'))
                    ->first();
            });
        return $acc->hasUser()
            ? $this->userAccount($acc->user, $currency->id)
            : $this->oauthAccount($acc, $currency->id);
    }

    public function getByOauthAccounts(array $accountIds, int $currencyId = null)
    {
        $ret = collect();
        $currency = Currency::query()
            ->where('id', $currencyId)
            ->firstOr(function() {
                return Currency::query()
                    ->where('alias', config('app.default_currency', 'DC'))
                    ->first();
            });
        $accountsWithoutUser = OAuthAccount::query()
            ->with('currencyAccounts')
            ->whereIn('account_id', $accountIds)
            ->wheredoesntHave('user')
            ->get();
        foreach ($accountsWithoutUser as $account) {
            $currencyAccount =
                $account->currencyAccounts->where('currency_id', $currency->id)->first()
                ??
                CurrencyAccount::query()
                    ->create(
                        [
                            'currency_id' => $currency->id,
                            'owner_id' => $account->id,
                            'owner_type' => OAuthAccount::class,
                            'id'  => Uuid::uuid4(),
                            'balance' => 0,
                            'closed' => false,
                        ]
                    );
            $ret->push($currencyAccount);
        }
        $accountsWithUser = OAuthAccount::query()
            ->with('user.currencyAccounts')
            ->whereHas('user')
            ->whereIn('account_id', $accountIds)
            ->get();
        foreach ($accountsWithUser as $account) {
            $currencyAccount =
                $account->user->currencyAccounts->where('currency_id', $currency->id)->first()
                ??
                CurrencyAccount::query()
                    ->create([
                        'currency_id' => $currency->id,
                        'owner_id' => $account->user->id,
                        'owner_type' => User::class,
                    ]);
            $ret->push($currencyAccount);
        }
        return $ret;
    }
}
