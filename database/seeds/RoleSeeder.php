<?php

use Illuminate\Database\Seeder;
use App\Models\User\Role;

class RoleSeeder extends Seeder
{
	public const ROLES = [
		'user',
		'admin',
        'premium user',
        'vip user'
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (static::ROLES as $name){
            Role::query()->firstOrCreate(['name' => $name]);
        }
    }
}
