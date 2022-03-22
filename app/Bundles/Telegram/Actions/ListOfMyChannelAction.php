<?php

namespace App\Bundles\Telegram\Actions;

use App\Models\TGUser;
use Illuminate\Support\Facades\Log;
use App\Traits\Telegram\RequestTrait;

class ListOfMyChannelAction
{
  use RequestTrait;

  /**
   * Handler for send list of users channel action
   *
   * @param int $chatId
   * 
   * @return bool
   */
  static function make(int $chatId): bool
  {
    $text = "Ваш список каналов временно пуст. Добавьте каналы в этом роботе и получайте ленту необходимых новостей и событий.";

    $user = TGUser::where('tg_user_id', $chatId)->first();
    $listOfUserChannels = $user->subscriptions();

    if ($listOfUserChannels->count() > 0) {
      $text = "Вот каналы, на которые вы подписаны:";

      foreach ($listOfUserChannels->get() as $key => $channel) {
        $text .= ($channel->info->tg_channel_title ? $channel->info->tg_channel_title : $channel->info->tg_channel_name) . "
";

        // CALLBACK BUTTONS 🗑
        self::apiRequest('sendMessage', [
          'chat_id' => $chatId,
          'text' => $text
        ]);
      }
    } else {
      self::apiRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => $text
      ]);
    }

    return true;
  }
}
