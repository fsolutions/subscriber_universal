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
            $table->unsignedBigInteger('tg_user_id');
            $table->bigInteger('tg_bot_channel_subscription_id');
            // $table->foreign('tg_user_id')->references('tg_user_id')->on('tg_users');
            // $table->foreign('tg_bot_channel_subscription_id')->references('tg_channel_id')->on('tg_bot_channel_subscribtions');
            $table->softDeletes('deleted_at', 0);
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
