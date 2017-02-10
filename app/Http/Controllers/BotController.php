<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class botController extends Controller
{
    public function test()
    {
        $string = '{"events":[{"type":"message","replyToken":"e593a9cc1e834791bf8076f6ff8ec116","source":{"userId":"U7cbf49ac38f334e5977af0d737c5bae0","type":"user"},"timestamp":1486692739451,"message":{"type":"text","id":"5625522229919","text":"22"}}]}';

        $decode = json_decode($string);
    }

    public function callBack()
    {
        $token = env('CHANNEL_ACCESS_TOKEN');
        $secret = env('CHANNEL_SECRET');

        $bot = new \LINE\LINEBot(
          new \LINE\LINEBot\HTTPClient\CurlHTTPClient($token),
          ['channelSecret' => $secret]
        );

        $signature = $_SERVER["HTTP_".\LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

        $jsonString = file_get_contents('php://input');
        $decode = json_decode($jsonString);

        $replyToken = $decode->events[0]->replyToken;
        $mid = $decode->events[0]->message->id;
        $text = $decode->events[0]->message->text;


        file_put_contents("php://stderr", "$replyToken".PHP_EOL);
        file_put_contents("php://stderr", "$mid".PHP_EOL);
        file_put_contents("php://stderr", "$text".PHP_EOL);

        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text);
        $response = $bot->replyMessage($replyToken, $textMessageBuilder);
        if ($response->isSucceeded()) {
            file_put_contents("php://stderr", "ok".PHP_EOL);
            return;
        }
    }
}
