<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('billing_id');
            $table->decimal('amount', 15, 3);
            $table->decimal('balance', 15, 3);
            $table->decimal('exchange_rate', 15, 2);
            $table->boolean('closed')->default(false)->index();
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
        Schema::dropIfExists('orders');
    }
}
