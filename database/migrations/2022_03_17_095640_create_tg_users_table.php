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
            $table->unsignedBigInteger('tg_user_id')->unique()->comment('ID в telegram');
            $table->unsignedInteger('user_id')->nullable()->comment('ID в таблице пользователей, если есть регистрация');
            $table->string('tg_username')->nullable()->comment('Username в telegram');
            $table->softDeletes('deleted_at', 0);
            $table->timestamps();
            $table->primary(['tg_user_id']);
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
