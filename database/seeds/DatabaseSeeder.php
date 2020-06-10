<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       // $this->call(OldDbSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(RoleSystemSeeder::class);
        // $this->call(LeagueRowSeeder::class);
    }
}
