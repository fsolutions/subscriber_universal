<?php

namespace App\Bundles\Telegram\Actions;

use App\Models\TGUser;
use App\Traits\Telegram\RequestTrait;
use App\Traits\Telegram\MakeComponents;

class ListOfMyChannelAction
{
  use RequestTrait;
  use MakeComponents;

  /**
   * Handler for send list of users channel action
   *
   * @param int $chatId
   * @param string $requestType
   * @param int $message_id
   * 
   * @return bool
   */
  static function make(int $chatId, int $message_id = -1, string $requestType = 'sendMessage'): bool
  {
    $text = "Ваш список каналов временно пуст. Добавьте каналы в этом роботе и получайте ленту необходимых новостей и событий.";

    $user = TGUser::where('tg_user_id', $chatId)->first();
    $listOfUserChannels = $user->subscriptions();

    if ($listOfUserChannels->count() > 0) {
      $text = "Кликните на канал, чтобы удалить его из списка подписок. Вот каналы, на которые вы подписаны:";

      $options = [];
      $row = -1;
      foreach ($listOfUserChannels->get() as $key => $channel) {
        if ($key % 2 == 0) {
          $row++;
          $options[$row] = [];
        }

        $channelCuttedName = mb_substr($channel->info->tg_channel_title ? $channel->info->tg_channel_title : $channel->info->tg_channel_name, 0, 13);

        $options[$row][] = [
          'text' => $channelCuttedName . ' ❌',
          'callback_data' => 'delete_channel||' . $channel->id
        ];
      }

      self::apiRequest($requestType, [
        'chat_id' => $chatId,
        'text' => $text,
        'reply_markup' => self::inlineKeyboardBtn($options),
        'message_id'   => $message_id,
      ]);
    } else {
      self::apiRequest($requestType, [
        'chat_id' => $chatId,
        'text' => $text,
        'message_id'   => $message_id,
      ]);
    }

    return true;
  }
}
