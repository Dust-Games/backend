<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class OldDbSeeder extends Seeder
{
    protected $db;

    protected const TABLES = [
        'oauth_account',
        'user',
        'unregistered_billing',
        'billing',
        'transaction',
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $db = DB::connection('backup');

        foreach (static::TABLES as $table) {
            $rows = $db->table($table)->orderBy('id')->chunk(1000, function ($rows) use ($table) {
                $rows = $rows->toArray();

                $rows = array_map(function ($obj) {
                    return (array) $obj;
                }, $rows);

                DB::table($table)->insert($rows);
            });
        }
    }

    protected function objectsToArrays(array $objects)
    {
        return array_map(function ($obj) {
            return (array) $obj;
        }, $objects);
    }
}
