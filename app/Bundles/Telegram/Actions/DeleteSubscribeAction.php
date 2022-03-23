<?php

namespace App\Bundles\Telegram\Actions;

use Throwable;
use App\Models\TGUserSubscription;
use Illuminate\Support\Facades\Log;
use App\Traits\Telegram\RequestTrait;
use App\Bundles\Telegram\Actions\ListOfMyChannelAction as TelegramListOfMyChannelAction;

class DeleteSubscribeAction
{
  use RequestTrait;

  /**
   * Handler for delete subscribe action with channel deleting by id of subscribe
   *
   * @param int $userSubscribtionId
   * @param int $chatId
   * @param int $message_id
   * 
   * @return string
   */
  static function delete(int $userSubscribtionId, int $chatId, int $message_id): string
  {
    $subscribtion = TGUserSubscription::find($userSubscribtionId);

    if ($subscribtion) {
      $text = "Канал успешно удален из подписок.";

      try {
        $subscribtion->delete();
        Log::info("Deleting channel subscribe with id: " . $userSubscribtionId . ' success');
      } catch (Throwable $e) {
        Log::error("Cant delete channel subscribe with id: " . $userSubscribtionId);
      }

      TelegramListOfMyChannelAction::make($chatId, $message_id, 'editMessageText');
    } else {
      $text = "Видимо подписка уже удалена, так как мы не нашли этот канал в списке ваших подписок.";
    }

    return $text;
  }
}
