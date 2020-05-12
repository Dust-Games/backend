<?php

use Illuminate\Database\Seeder;

use App\Models\LeagueRow;
use Faker\Generator as Faker;

class LeagueRowSeeder extends Seeder
{
    private const ROWS_COUNT = 1000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = resolve(Faker::class);
        $rows = [];

        for ($i=0; $i < static::ROWS_COUNT; $i++) { 
            $rows[] = $this->makeRow();
        }

        $rows = array_merge(
            $rows, 
            [
                $this->makeRow('testest', 1),
                $this->makeRow('testest', 2),
            ]
        );

        LeagueRow::insert($rows);
    }

    private function makeRow($account_id = null, $week = null)
    {
    	return [
            'account_id' => $account_id ?? random_int(1, 100000),
            'username' => $this->faker->name,
            'week' => $week ?? random_int(1, 16),
            'class' => random_int(1, 5),
            'score' => random_int(0, 1000),
        ];
    }
}
