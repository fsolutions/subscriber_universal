<?php

namespace App\Bundles\Telegram\Actions;

use App\Models\TGUser;
use Illuminate\Support\Facades\Log;
use App\Traits\Telegram\RequestTrait;

class ListOfMyChannelAction
{
  use RequestTrait;
  use MakeComponents;

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

      $options = [];
      $row = -1;
      foreach ($listOfUserChannels->get() as $key => $channel) {
        if ($key % 2 == 0) {
          $row++;
          $options[$row] = [];
        }

        $options[$row][] = [
          'text' => $channel->info->tg_channel_title ? $channel->info->tg_channel_title : $channel->info->tg_channel_name,
          'callback_data' => $channel->tg_bot_channel_subscription_id
        ];

        // CALLBACK BUTTONS 🗑
        self::apiRequest('sendMessage', [
          'chat_id' => $chatId,
          'text' => $text,
          'reply_markup' => self::inlineKeyboardBtn($options)
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
