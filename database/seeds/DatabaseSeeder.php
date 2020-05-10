<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([['key' => 'league_week', 'value' => 1]]);

        $this->call(OldDbSeeder::class);
        $this->call(RoleSystemSeeder::class);
        $this->call(LeagueRowSeeder::class);
    }
}
