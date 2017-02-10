<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class botController extends Controller
{
    public function test()
    {
        $lineTestString = '{"events":[{"type":"message","replyToken":"e593a9cc1e834791bf8076f6ff8ec116","source":{"userId":"U7cbf49ac38f334e5977af0d737c5bae0","type":"user"},"timestamp":1486692739451,"message":{"type":"text","id":"5625522229919","text":"22"}}]}';
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

        //api
        $content = file_get_contents('http://asper-bot-rates.appspot.com/currency.json');
        $currency = json_decode($content);

        $result = $this->changeName($text, $currency);
        file_put_contents("php://stderr", "$text".PHP_EOL);
        file_put_contents("php://stderr", "json_encode($result)".PHP_EOL);

        /*if ( ! empty($result)) {
            //send
            foreach ($result as $key => $value) {
                $sendMsg = $key . " : " . $value;
                file_put_contents("php://stderr", "$sendMsg".PHP_EOL);
                $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($sendMsg);
                $response = $bot->replyMessage($replyToken, $textMessageBuilder);
            }
        }*/
    }

    /*
     * 美元(USD) 港幣(HKD) 英鎊(GBP) 澳幣(AUD) 加拿大幣(CAD) 新加坡幣(SGD) 瑞士法郎(CHF)
     * 日圓(JPY) 南非幣(ZAR) 瑞典克朗(SEK) 紐西蘭幣(NZD) 泰銖(THB) 菲律賓披索(PHP)
     * 印尼盾(IDR) 歐元(EUR) 菲律賓披索(KRW) 越南幣(VND) 馬來西亞幣(MYR) 人民幣(CNY)
     */
    public function changeName($typeName, $sourceData)
    {
        switch ($typeName) {
            case "日幣":
                $money = $sourceData->rates->JPY;

                return [
                    '買入現金' => $money->buyCash,
                    '買入即期' => $money->buySpot,
                    '賣出現金' => $money->sellCash,
                    '賣出即期' => $money->sellSpot,
                    '更新時間' => Carbon::createFromTimestamp($sourceData->updateTime)->format('Y-m-d H:i:s'),
                ];

            default:
                return;
        }
    }
}
