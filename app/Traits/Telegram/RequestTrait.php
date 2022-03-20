<?php

namespace App\Traits\Telegram;

trait RequestTrait
{
  static function apiRequest($method, $parameters = [])
  {
    $url = "https://api.telegram.org/bot" . env('TELEGRAM_TOKEN') . '/' . $method;

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_HEADER, false);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($handle, CURLOPT_POST, 1);
    curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($parameters));
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($handle);
    curl_close($handle);

    if ($response === false) {
      return false;
    }

    $responseAnswer = json_decode($response, true);
    if ($responseAnswer['ok'] == false) {
      return false;
    }

    return $responseAnswer['result'];
  }
}
