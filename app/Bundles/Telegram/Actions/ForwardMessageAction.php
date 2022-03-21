<?php

namespace App\Bundles\Telegram\Actions;

use stdClass;
use App\Models\TGUserSubscription;
use App\Traits\Telegram\RequestTrait;

class ForwardMessageAction
{
  use RequestTrait;

  /**
   * Handler for foward message action
   *
   * @param stdClass $requestData
   *
   * @return bool
   */
  static function make(stdClass $requestData): bool
  {
    $activeChatsForMessages = TGUserSubscription::where(
      'tg_bot_channel_subscription_id',
      $requestData->message->forward_from_chat->id
    )->get();

    foreach ($activeChatsForMessages as $chat) {
      self::apiRequest('forwardMessage', [
        'chat_id' => $chat->tg_user_id,
        'from_chat_id' => $requestData->message->from->id,
        'message_id' => $requestData->message->message_id,
        'disable_notification' => false
      ]);
    }

    return true;
  }
}
