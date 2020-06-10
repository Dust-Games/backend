<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('commission', 15, 2);
            $table->timestamps();
        });
        Schema::create('sub_role_user', function (Blueprint $table) {
            $table->foreignId('sub_role_id')->constrained();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_role_user');
        Schema::dropIfExists('sub_roles');
    }
}
