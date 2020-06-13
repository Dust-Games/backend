<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDustCoinTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dust_coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('operation_id');
            $table->string('operation_type');
            $table->uuid('billing_id');
            $table->boolean('debt')->default(false);
            $table->timestamps();
            $table->foreign('billing_id')->references('id')->on('billing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dust_coin_transactions');
    }
}
