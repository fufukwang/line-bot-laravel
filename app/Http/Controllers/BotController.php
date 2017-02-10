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

        $jsonString = file_get_contents('php://input');
        $jsonObject = json_decode($jsonString);

        file_put_contents("php://stderr", "$jsonObject".PHP_EOL);



    }
}
