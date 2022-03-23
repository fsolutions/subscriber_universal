<?php

namespace App\Bundles\Telegram\Callbacks;

use Illuminate\Support\Facades\Log;
use App\Traits\Telegram\RequestTrait;
use App\Bundles\Telegram\Actions\DeleteSubscribeAction;

class CallbackCombineAction
{
  use RequestTrait;

  /**
   * Handler of callback query
   *
   * @param string $callback_query
   * @param int $callback_query_id
   * @param int $chatId
   * @param int $message_id
   * 
   * @return bool
   */
  static function handler(string $callback_query, int $callback_query_id, int $chatId, int $message_id): bool
  {
    $findCommand = explode('||', $callback_query);
    $text = '';

    switch ($findCommand[0]) {
      case 'delete_channel':
        $text = DeleteSubscribeAction::delete($findCommand[1], $chatId, $message_id);
        break;

      default:
        Log::info('Unknown callback_query operation: ' . $callback_query);
        break;
    }

    self::apiRequest('answerCallbackQuery', [
      'callback_query_id' => $callback_query_id,
      'text' => $text
    ]);

    return true;
  }
}
