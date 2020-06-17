<?php

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    private const CURRENCIES = [
        ['name' => 'Dust coin', 'alias' => 'DC'],
        ['name' => 'USD Token', 'alias' => 'USDT'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach (static::CURRENCIES as $CURRENCY) {
            Currency::query()->firstOrCreate($CURRENCY);
        }
    }
}
