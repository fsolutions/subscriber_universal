<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTgUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tg_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tg_user_id')->unique();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('tg_username')->nullable();
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
        Schema::dropIfExists('tg_users');
    }
}
