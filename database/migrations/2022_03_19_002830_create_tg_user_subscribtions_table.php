<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTgUserSubscribtionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tg_user_subscribtions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('tg_user_id')->constrained('tg_users');
            $table->unsignedInteger('tg_bot_channel_subscription_id')->nullable();
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
        Schema::dropIfExists('tg_user_subscribtions');
    }
}
