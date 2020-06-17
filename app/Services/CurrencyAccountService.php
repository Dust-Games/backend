<?php


namespace App\Services;


use App\Models\Currency;
use App\Models\CurrencyAccount;
use App\Models\OAuthAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

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
            ->firstOrCreate([
                'currency_id' => $currencyId,
                'owner_id' => $acc->id,
                'owner_type' => OAuthAccount::class,
            ]);
    }

    /**
     * @param OAuthAccount $acc
     * @param int|null $currencyId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function getByOauthAccount(OAuthAccount $acc, int $currencyId = null)
    {
        return $acc->hasUser()
            ? $this->userAccount($acc->user, $currencyId)
            : $this->oauthAccount($acc, $currencyId);
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
            ->wheredoesntHave('user', function (Builder $query) use ($accountIds) {
                $query->whereIn('account_id', $accountIds);
            })
            ->get();
        foreach ($accountsWithoutUser as $account) {
            $currencyAccount =
                $account->currencyAccounts->where('currency_id', $currency->id)->first()
                ??
                CurrencyAccount::query()
                    ->create([
                        'currency_id' => $currency->id,
                        'owner_id' => $account->id,
                        'owner_type' => OAuthAccount::class,
                    ]);
            $ret->push($currencyAccount);
        }
        $accountsWithUser = OAuthAccount::query()
            ->with('user.currencyAccounts')
            ->whereHas('user', function (Builder $query) use ($accountIds) {
                $query->whereIn('account_id', $accountIds);
            })
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
