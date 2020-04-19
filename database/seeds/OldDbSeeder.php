<?php

use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class OldDbSeeder extends Seeder
{
    protected $db;

    protected function seed() {

        return [
            'account_type:oauth_account' => function ($db, $old_name, $new_name) {

                $old = $db->table($old_name)->get()->toArray();

                $new = [];
                foreach ($old as $row) {
                    $new[] = [
                        'id' => (string) Uuid::uuid4(),
                        'user_id' => null,
                        'oauth_provider_id' => $row->type,
                        'account_id' => $row->account_id,
                        'username' => $row->username,
                    ];
                }

                DB::table($new_name)->insert($new);
            },

            'transaction:transaction' => function ($db, $old_name, $new_name) {

                $old = $db->table($old_name)->get()->toArray();

                $new = [];
                foreach ($old as $row) {
                    $new[] = [
                        'id' => $row->id,
                        'user_id' => $row->unregistered_user_id,
                        'is_registered' => false,
                        'type' => $row->type,
                        'status' => $row->status,
                        'currency_num' => $row->currency_num,
                        'created_at' => Carbon::createFromTimestamp($row->created_at)->toDateTimeString(),
                    ];
                }

                DB::table($new_name)->insert($new);
            },

            'unregistered:unregistered_billing' => function ($db, $old_name, $new_name) {

                $old = $db->table($old_name)->get()->toArray();

                $oauth_account = DB::table('oauth_account')->get();

                $now = now();
                $new = [];
                foreach ($old as $row) {
                    $new[] = [
                        'id' => $row->id,
                        'oauth_provider_id' => $row->type,
                        'oauth_account_id' => $oauth_account->where('account_id', $row->account_id)->first()->id,
                        'dust_token_num' => $row->dust_token_num,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                DB::table($new_name)->insert($new);
            },
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $db = DB::connection('backup');

        foreach ($this->seed() as $name => $closure) {
            $tables = explode(':', $name);

            $closure($db, $tables[0], $tables[1]);
        }
    }
}