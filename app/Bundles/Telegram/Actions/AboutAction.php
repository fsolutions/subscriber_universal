<?php

namespace App\Bundles\Telegram\Actions;

use App\Models\TGUser;
use App\Traits\Telegram\RequestTrait;
use App\Traits\Telegram\MakeComponents;

class AboutAction
{
  use RequestTrait;
  use MakeComponents;

  /**
   * Handler for start action
   *
   * @param int $chatId
   * @param int $userId
   * @param string $userName
   * 
   * @return bool
   */
  static function make(int $chatId): bool
  {
    $text = "*Thank you for using our bot*

Our website: https://news-feeder-bot.are-u-happy.com
    
Write me in Telegram if you have questions: @fomichevms

My e-mail: fomichevms@gmail.com";

    self::apiRequest('sendMessage', [
      'chat_id' => $chatId,
      'text' => $text
    ]);

    return true;
  }
}
