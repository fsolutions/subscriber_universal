<?php

namespace App\Bundles\Telegram\Actions;

use App\Traits\Telegram\RequestTrait;

class EmptyAction
{
  use RequestTrait;

  /**
   * Handler for empty action
   *
   * @param int $chatId
   *
   * @return bool
   */
  static function make(int $chatId): bool
  {
    $text = "Хотелось бы узнать поточнее, что бы вы хотели. Попробуйте начать с команды /start";

    self::apiRequest('sendMessage', [
      'chat_id' => $chatId,
      'text' => $text
    ]);

    return true;
  }
}
