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
        file_put_contents("php://stderr", "sending push !!!".PHP_EOL);

        /*$signature = $_SERVER["HTTP_".\LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
        $body = file_get_contents("php://input");
        try {
          $events = $bot->parseEventRequest($body, $signature);

        } catch (Exception $e) {
        }*/

    }
}
