<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTgChannelTitleToTgBotChannelSubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tg_bot_channel_subscribtions', function (Blueprint $table) {
            $table->string('tg_channel_title')->nullable()->after('tg_channel_name')->comment('Наименование канала');
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
            $table->dropColumn('tg_channel_title');
        });
    }
}
