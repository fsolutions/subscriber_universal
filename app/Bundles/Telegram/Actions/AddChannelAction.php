<?php

namespace App\Bundles\Telegram\Actions;

use App\Models\TGUserSubscription;
use App\Traits\Telegram\RequestTrait;
use App\Models\TGBotChannelSubscribtion;

class AddChannelAction
{
  use RequestTrait;

  /**
   * Handler for add channel action with start message
   *
   * @param int $chatId
   * 
   * @return bool
   */
  static function start(int $chatId): bool
  {
    $text = "Введите в строку ввода ссылку на канал (например, https://t.me/channel_name) или на наименование канала в формате @channel_name и нажмите отправить.";

    self::apiRequest('sendMessage', [
      'chat_id' => $chatId,
      'text' => $text
    ]);

    return true;
  }

  /**
   * Handler for add channel action with channel name operation
   *
   * @param int $chatId
   * @param int $userId
   * @param string $channelName
   * 
   * @return bool
   */
  static function add(int $chatId, int $userId, string $channelName): bool
  {
    $text = "К сожалению, мы не смогли найти канал с таким именем, попробуйте скопировать название из канала и ввести его снова.";

    $channel = self::apiRequest('getChat', [
      'chat_id' => $channelName
    ]);

    if ($channel != false) {
      $text = "Мы успешно подписали Вас на выбранный канал!";

      TGBotChannelSubscribtion::updateOrCreate(
        ['tg_channel_id' => $channel['id']],
        ['tg_channel_name' => $channel['username'], 'tg_channel_title' => $channel['title']]
      );

      TGUserSubscription::updateOrCreate(
        ['tg_user_id' => $userId, 'tg_bot_channel_subscription_id' => $channel['id']],
        ['tg_bot_channel_subscription_id' => $channel['id']]
      );
    }

    self::apiRequest('sendMessage', [
      'chat_id' => $chatId,
      'text' => $text
    ]);

    return true;
  }
}
