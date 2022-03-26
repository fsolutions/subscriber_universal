<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTgChannelLastMessageIdToTgBotChannelSubscribtions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tg_bot_channel_subscribtions', function (Blueprint $table) {
            $table->unsignedBigInteger('tg_channel_last_message_id')->nullable()->after('tg_channel_title')->comment('Last sended to bot message_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tg_bot_channel_subscribtions', function (Blueprint $table) {
            $table->dropColumn('tg_channel_last_message_id');
        });
    }
}
