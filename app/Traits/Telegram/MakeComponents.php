<?php

namespace App\Traits\Telegram;

trait MakeComponents
{
  /**
   * Keyboard buttons
   *
   * @param array $options
   * @return string
   */
  static function keyboardBtn(array $options): string
  {
    $keyboard = [
      'keyboard' => $options,
      'resize_keyboard' => true,
      'one_time_keyboard' => false,
      'selective' => true
    ];

    return json_encode($keyboard);
  }

  /**
   * Inline keyboard buttons
   *
   * @param array $options
   * @return string
   */
  static function inlineKeyboardBtn(array $options): string
  {
    $keyboard = [
      'inline_keyboard' => $options,
      'resize_keyboard' => true,
      'one_time_keyboard' => false,
      'selective' => true
    ];

    return json_encode($keyboard);
  }
}
