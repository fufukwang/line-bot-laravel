<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class botController extends Controller
{
    public function test()
    {

    }

    public function callBack()
    {
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('CHANNEL_ACCESS_TOKEN'));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('CHANNEL_SECRET')]);

        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello');
        $response = $bot->replyMessage('<reply token>', $textMessageBuilder);

        $jsonString = file_get_contents('php://input');
        $jsonObject = json_decode($jsonString);

        //取得MID
        $targetMID = $jsonObject->{"result"}[0]->{"content"}->{"from"};
        //取得訊息
        $message = $jsonObject->{"result"}[0]->{"content"}->{"text"};

        $bot->sendText([$targetMID], $message);

    }
}
