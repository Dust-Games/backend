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

        LeagueRow::insert($rows);
    }

    private function makeRow()
    {
    	return [
            'account_id' => random_int(1000000000, 9999999999),
            'username' => $this->faker->name,
            'week' => random_int(1, 16),
            'class' => random_int(1, 5),
            'score' => random_int(0, 1000),
        ];
    }
}
