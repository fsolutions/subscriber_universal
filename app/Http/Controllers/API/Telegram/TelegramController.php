<?php

namespace App\Http\Controllers\API\Telegram;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Traits\Telegram\RequestTrait;
use App\Traits\Telegram\MakeComponents;

class TelegramController extends Controller
{
    use RequestTrait;
    use MakeComponents;

    /**
     * Set webhook method
     *
     * @return void
     */
    public function webhook()
    {
        return $this->apiRequest('setWebhook', [
            'url' => route('webhook')
        ]) ? ['success'] : ['setWebhook problems'];
    }

    public function index(Request $request)
    {
        Log::debug('request', $request->all());
        $result = json_decode(file_get_contents('php://input'));

        // {"update_id":795176229,"message":
        //     {"message_id":5,
        //         "from":{"id":466136843,"is_bot":false,"first_name":"Mikhail","username":"fomichevms","language_code":"ru"},
        //         "chat":{"id":466136843,"first_name":"Mikhail","username":"fomichevms","type":"private"},
        //         "date":1647504351,
        //         "text":"/start",
        //         "entities":
        //         [{"offset":0,"length":6,"type":"bot_command"}]}
        // } 
        $action = isset($result->message->text) ? $result->message->text : '';
        $chatId = $result->message->chat->id;
        // $userFirstName = $result->message->from->first_name;
        // $userName = $result->message->from->username ? $result->message->from->username : '';

        if ($action == '/start') {
            $text = "Добро пожаловать в организованную ленту каналов!";

            $options = [
                [
                    ['text' => "Добавить канал", 'callback_data' => 'add_new_channel'],
                    ['text' => "Список моих каналов", 'callback_data' => 'see_list_of_channels']
                ]
            ];

            $this->apiRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => $text,
                'reply_markup' => $this->keyboardBtn($options)
            ]);
        } else if (isset($result->message->forward_from_chat)) {
            $this->apiRequest('forwardMessage', [
                'chat_id' => 466136843, // NOW IT CHAT OF FOMICHEVMS AND BOT
                'from_chat_id' => $result->message->from->id,
                'message_id' => $result->message->message_id,
                'disable_notification' => false
            ]);
        }
    }
}
