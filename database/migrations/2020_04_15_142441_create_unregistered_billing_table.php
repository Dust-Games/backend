<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnregisteredBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unregistered_billing', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('oauth_account_id');
            $table->decimal('dc_token_num', 15, 3)->default(0);
            $table->timestamps();

            $table->foreign('oauth_account_id')
                ->references('id')
                ->on('oauth_account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unregistered_billing');
    }
}
