<?php

namespace App\Bundles\Telegram\Actions;

use App\Models\TGUser;
use App\Traits\Telegram\RequestTrait;
use App\Traits\Telegram\MakeComponents;

class StartAction
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
  static function make(int $chatId, int $userId, string $userName): bool
  {
    $text = "*Добро пожаловать в организованную ленту каналов!*
    
Наш бот позволяет подписываться на каналы, не устраивая мусорку в Ваших чатах. Просто добавьте нужные Вам каналы.

Меню ниже поможет Вам сориентироваться.";

    $options = [
      [
        ['text' => "Добавить канал", 'callback_data' => 'add_new_channel'],
        ['text' => "Список моих каналов", 'callback_data' => 'see_list_of_channels']
      ]
    ];

    self::apiRequest('sendMessage', [
      'chat_id' => $chatId,
      'text' => $text,
      'reply_markup' => self::keyboardBtn($options)
    ]);

    TGUser::updateOrCreate(
      [
        'tg_user_id' => $userId
      ],
      [
        'tg_username' => $userName,
      ]
    );

    return true;
  }
}
