<?php

use Illuminate\Database\Seeder;

class RoleSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$this->call(RoleSeeder::class);
    	$this->call(PermissionSeeder::class);
    	$this->call(RolePermissionSeeder::class);
    	$this->call(SubRoleSeeder::class);
    }
}
