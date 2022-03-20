<?php

namespace App\Bundles\Telegram\Actions;

use stdClass;
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
    self::apiRequest('forwardMessage', [
      'chat_id' => 466136843, // NOW IT CHAT OF FOMICHEVMS AND BOT
      'from_chat_id' => $requestData->message->from->id,
      'message_id' => $requestData->message->message_id,
      'disable_notification' => false
    ]);

    return true;
  }
}
