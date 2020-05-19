<?php

use Illuminate\Database\Seeder;
use App\Models\Settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::query()->firstOrCreate([
            'key' => 'league_week',
            'value' => 1,
        ]);
        Settings::query()->firstOrCreate([
           'key' => 'tournament_start_date',
            'value' => '2020-05-04',
        ]);
    }
}
