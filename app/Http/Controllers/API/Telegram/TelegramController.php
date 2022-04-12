<?php

namespace App\Http\Controllers\API\Telegram;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Traits\Telegram\RequestTrait;
use App\Bundles\Telegram\Actions\AboutAction as TelegramAboutAction;
use App\Bundles\Telegram\Actions\EmptyAction as TelegramEmptyAction;
use App\Bundles\Telegram\Actions\StartAction as TelegramStartAction;
use App\Bundles\Telegram\Actions\AddChannelAction as TelegramAddChannelAction;
use App\Bundles\Telegram\Actions\ForwardMessageAction as TelegramForwardMessageAction;
use App\Bundles\Telegram\Actions\ListOfMyChannelAction as TelegramListOfMyChannelAction;
use App\Bundles\Telegram\Callbacks\CallbackCombineAction as TelegramCallbackCombineAction;

class TelegramController extends Controller
{
    use RequestTrait;

    /**
     * Set webhook method
     *
     * @return void
     */
    public function webhook()
    {
        return self::apiRequest('setWebhook', [
            'url' => route('webhook')
        ]) ? ['success'] : ['setWebhook problems'];
    }

    /**
     * Action enter point for telegram bot
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function index(Request $request): bool
    {
        // Список команд:
        // start - Начать работу с ботом (Start work with bot)
        // add - Добавить новый канал (Add new channel)
        // list - Список моих каналов (List of my channels)
        // about - О создателях (About dev team)

        Log::debug('request', $request->all());
        $requestData = json_decode(file_get_contents('php://input'));

        $action = isset($requestData->message->text) ? $requestData->message->text : '';
        $callback_query = isset($requestData->callback_query) ? $requestData->callback_query->data : '';

        $chatId = isset($requestData->message->chat->id) ? $requestData->message->chat->id : -1;
        $message_id = isset($requestData->message->message_id) ? $requestData->message->message_id : -1;
        $userId = isset($requestData->message->from->id) ? $requestData->message->from->id : $chatId;
        $userName = isset($requestData->message->from->username) ? $requestData->message->from->username : '';
        $channelName = $this->channelNameExecution($action);

        if ($callback_query != '') {
            $callback_query_id = $requestData->callback_query->id;
            $chatId = $requestData->callback_query->message->chat->id;
            $message_id = $requestData->callback_query->message->message_id;
            TelegramCallbackCombineAction::handler($callback_query, $callback_query_id, $chatId, $message_id);
        } else {
            if ($action == '/start') {
                TelegramStartAction::make($chatId, $userId, $userName);
            } else if ($action == '/about') {
                TelegramAboutAction::make($chatId);
            } else if ($action == '/add' || $action == 'Добавить канал') {
                TelegramAddChannelAction::start($chatId);
            } else if ($action == '/list' || $action == 'Список моих каналов') {
                TelegramListOfMyChannelAction::make($chatId);
            } else if ($channelName != '') {
                TelegramAddChannelAction::add($chatId, $userId, $channelName);
            } else if (isset($requestData->message->forward_from_chat)) {
                TelegramForwardMessageAction::make($requestData);
            } else {
                TelegramEmptyAction::make($chatId);
            }
        }

        return true;
    }

    /**
     * Channel name execution
     *
     * @param string $text
     * @return string
     */
    public function channelNameExecution(string $text): string
    {
        $text = preg_replace('/\s+/', '', $text);
        $text = str_replace('https://t.me/', '@', $text);
        if (preg_match('/@/', $text) == 1) {
            return $text;
        }

        return '';
    }
}
