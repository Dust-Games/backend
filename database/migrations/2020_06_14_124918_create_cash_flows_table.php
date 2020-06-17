<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('debt_id')->nullable();
            $table->uuid('credit_id')->nullable();
            $table->decimal('amount', 15, 3);
            $table->uuid('operation_id');
            $table->string('operation_type');
            $table->timestamps();
            $table->foreign('debt_id')->references('id')->on('currency_accounts');
            $table->foreign('credit_id')->references('id')->on('currency_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_flows');
    }
}
