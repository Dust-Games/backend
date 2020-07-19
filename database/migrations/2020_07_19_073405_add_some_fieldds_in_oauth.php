<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFielddsInOauth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oauth_account', function (Blueprint $table) {
            $table->integer('duel_rating')->default(0);
            $table->integer('number_of_games')->default(0);
            $table->integer('number_of_wins')->default(0);
            $table->integer('total_bets')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_account', function (Blueprint $table) {
            //
        });
    }
}
