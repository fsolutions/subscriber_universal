<?php

namespace App\Http\Controllers\API\Telegram;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Traits\Telegram\RequestTrait;
use App\Bundles\Telegram\Actions\EmptyAction as TelegramEmptyAction;
use App\Bundles\Telegram\Actions\StartAction as TelegramStartAction;
use App\Bundles\Telegram\Actions\ForwardMessageAction as TelegramForwardMessageAction;

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

    public function index(Request $request)
    {
        Log::debug('request', $request->all());
        $requestData = json_decode(file_get_contents('php://input'));

        // {"update_id":795176229,"message":
        //     {"message_id":5,
        //         "from":{"id":466136843,"is_bot":false,"first_name":"Mikhail","username":"fomichevms","language_code":"ru"},
        //         "chat":{"id":466136843,"first_name":"Mikhail","username":"fomichevms","type":"private"},
        //         "date":1647504351,
        //         "text":"/start",
        //         "entities":
        //         [{"offset":0,"length":6,"type":"bot_command"}]}
        // } 
        $action = isset($requestData->message->text) ? $requestData->message->text : '';
        $chatId = isset($requestData->message->chat->id) ? $requestData->message->chat->id : -1;
        $userId = isset($requestData->message->from->id) ? $requestData->message->from->id : $chatId;
        $userName = isset($requestData->message->from->username) ? $requestData->message->from->username : '';

        if ($action == '/start') {
            TelegramStartAction::make($chatId, $userId, $userName);
        } else if (isset($requestData->message->forward_from_chat)) {
            TelegramForwardMessageAction::make($requestData);
        } else {
            TelegramEmptyAction::make($chatId);
        }
    }
}
