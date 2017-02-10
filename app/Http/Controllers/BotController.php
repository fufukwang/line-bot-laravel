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
        $token = env('CHANNEL_ACCESS_TOKEN');
        $secret = env('CHANNEL_SECRET');

        $bot = new \LINE\LINEBot(
          new \LINE\LINEBot\HTTPClient\CurlHTTPClient($token),
          ['channelSecret' => $secret]
        );

        $signature = $_SERVER["HTTP_".\LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
        $body = file_get_contents("php://input");

        try {
            $events = $bot->parseEventRequest($body, $signature);
            $json = json_encode($events);
            file_put_contents("php://stderr", "$json".PHP_EOL);
        } catch (Exception $e) {
            file_put_contents("php://stderr", "$e".PHP_EOL);
        }

    }
}
