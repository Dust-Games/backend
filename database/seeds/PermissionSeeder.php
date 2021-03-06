<?php

use Illuminate\Database\Seeder;
use App\Models\User\Permission;

class PermissionSeeder extends Seeder
{
	public const DELIMITER = '-';

	public const ENTITIES = [
		'transaction',
		'oauth_account',
		'user',
		'session',
		'billing',
		'unregistered_billing',
	];

	public const ACTIONS = [
		'view_any',
		'view',
		'create',
		'update',
		'delete',
		'restore',
		'force_delete',
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (static::ENTITIES as $entity) {
        	foreach (static::ACTIONS as $action) {
        		Permission::query()->firstOrCreate(
        		    [ 'value' => static::makePermission($action, $entity) ]
                );
        	}
        }
    }

    public static function makePermission(string $permission, string $entity)
    {
    	return $permission . static::DELIMITER . $entity;
    }
}
