<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league', function (Blueprint $table) {
            $table->id();

            $table->string('account_id');
            $table->string('username', 100);
            $table->smallInteger('week');
            $table->smallInteger('class');
            $table->bigInteger('score')->default(0);

            $table->unique(['account_id', 'week']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league');
    }
}
