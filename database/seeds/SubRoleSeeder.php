<?php

use Illuminate\Database\Seeder;
use App\Models\User\SubRole;

class SubRoleSeeder extends Seeder
{
    public const SUB_ROLE_NAMES = ['Стример', 'Арбитр'];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $commission = 0;
        foreach (static::SUB_ROLE_NAMES as $name) {
            SubRole::query()->firstOrCreate(compact('name', 'commission'));
        }
    }
}
