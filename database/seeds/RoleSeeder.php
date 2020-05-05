<?php

use Illuminate\Database\Seeder;
use App\Models\User\Role;

class RoleSeeder extends Seeder
{
	public const ROLES = [
		'user',
		'admin',
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$roles = array_map(function ($name) {
    		return ['name' => $name];
    	}, static::ROLES);   

    	Role::insert($roles);
    }
}
