<?php

namespace App\Traits\Telegram;

trait MakeComponents
{
  private function keyboardBtn($options)
  {
    $keyboard = [
      'keyboard' => $options,
      'resize_keyboard' => true,
      'one_time_keyboard' => false,
      'selective' => true
    ];

    return json_encode($keyboard);
  }
}
