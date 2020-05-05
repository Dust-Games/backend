<?php

use Illuminate\Database\Seeder;
use App\Models\User\Role;
use App\Models\User\Permission;

class RolePermissionSeeder extends Seeder
{
	public const ROLE_PERMISSION = [
		'user' => [
			'transaction' => [
				'view',
			],
			'oauth_account' => [
				'view',
			],
			'unregistered_billing' => [
				'view'
			],
		],
		'admin' => [
			'transaction' => [
				'view_any',
				'view',
				'create',
				'update',
				'delete',
				'restore',
				'force_delete',
			],
			'oauth_account' => [
				'view_any',
				'view',
				'create',
				'update',
				'delete',
				'restore',
				'force_delete',
			],
			'unregistered_billing' => [
				'view_any',
				'view',
				'create',
				'update',
				'delete',
				'restore',
				'force_delete',
			],
		],
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$db_roles = Role::get();
    	$db_permissions = Permission::get();
    	$role_permission = [];

        foreach (static::ROLE_PERMISSION as $role => $entities) {
        	foreach ($entities as $entity => $permissions) {
        		foreach ($permissions as $perm) {
        			$role_permission[] = [
        				'role_id' => $db_roles->where('name', $role)->first()->getKey(),
        				'permission_id' => $db_permissions->where(
        					'value',
        					PermissionSeeder::makePermission($perm, $entity)
        				)->first()->getKey(), 
        			];
        		}
        	}
        }

        DB::table('role_permission')->insert($role_permission);
    }
}
