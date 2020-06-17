<?php

use Illuminate\Database\Seeder;
use App\Models\Billing;
use App\Models\Currency;
use App\Models\CurrencyAccount;
use App\Models\User;
use App\Models\UnregisteredBilling;
use App\Models\OAuthAccount;

class CurrencyAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $USDT = Currency::query()->firstWhere('alias', 'USDT');
        $DC = Currency::query()->firstWhere('alias', 'DC');
        Billing::query()->get()->each(function ($billing) use ($USDT, $DC) {
            CurrencyAccount::query()
             ->firstOrCreate(
                 [
                    'owner_id' => $billing->user_id,
                    'owner_type' => User::class,
                    'currency_id' => $USDT->id,
                 ],
                 [
                     'balance' => $billing->usd_tokens_num,
                 ]
             );
            CurrencyAccount::query()
                ->firstOrCreate(
                    [
                        'owner_id' => $billing->user_id,
                        'owner_type' => User::class,
                        'currency_id' => $DC->id,
                    ],
                    [
                        'balance' => $billing->dust_coins_num,
                    ]
                );
        });
        UnregisteredBilling::with('account.user')->chunk(100, function ($unregisredBillings) use ($DC) {
            $unregisredBillings->each(function ($unregisredBilling) use ($DC) {
                if (!$unregisredBilling->account->user) {
                    CurrencyAccount::query()
                        ->firstOrCreate(
                            [
                                'owner_id' => $unregisredBilling->oauth_account_id,
                                'owner_type' => OAuthAccount::class,
                                'currency_id' => $DC->id,
                            ],
                            [
                                'balance' =>  $unregisredBilling->dust_coins_num,
                            ]
                        );
                }
            });
        });
    }
}
