<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminCashAccountChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_currency_account_changes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['set', 'add', 'reduce']);
            $table->enum('way', ['bot', 'panel']);
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
        Schema::dropIfExists('admin_currency_account_changes');
    }
}
