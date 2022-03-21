<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTgBotChannelSubscribtionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tg_bot_channel_subscribtions', function (Blueprint $table) {
            $table->bigInteger('tg_channel_id')->unique()->comment('ID телеграм канала');
            $table->string('tg_channel_name')->nullable()->comment('Username телеграм канала');
            $table->timestamps();
            $table->primary(['tg_channel_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tg_bot_channel_subscribtions');
    }
}
