<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class botController extends Controller
{
    public function test()
    {
        //test string
        $lineTestString = '{"events":[{"type":"message","replyToken":"e593a9cc1e834791bf8076f6ff8ec116","source":{"userId":"U7cbf49ac38f334e5977af0d737c5bae0","type":"user"},"timestamp":1486692739451,"message":{"type":"text","id":"5625522229919","text":"22"}}]}';


        //api
        $content = file_get_contents('http://asper-bot-rates.appspot.com/currency.json');
        $currency = json_decode($content);

        $result = $this->changeName('韓幣', $currency);
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
        file_put_contents("php://stderr", "$result".PHP_EOL);

        if ( ! empty($result)) {
            //send
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($result);
            $bot->replyMessage($replyToken, $textMessageBuilder);
        }
    }

    /*
     * 美元(USD) 港幣(HKD) 英鎊(GBP) 澳幣(AUD) 加拿大幣(CAD) 新加坡幣(SGD) 瑞士法郎(CHF)
     * 日圓(JPY) 南非幣(ZAR) 瑞典克朗(SEK) 紐西蘭幣(NZD) 泰銖(THB) 菲律賓披索(PHP)
     * 印尼盾(IDR) 歐元(EUR) 韓幣(KRW) 越南幣(VND) 馬來西亞幣(MYR) 人民幣(CNY)
     */
    public function changeName($typeName, $sourceData)
    {
        //XDD have fun
        $funny = $this->funny($typeName);
        if ( ! empty($funny)) {
            return $funny;
        }

        //麻將
        $maJohn = $this->maJohn($typeName);
        if ( ! empty($maJohn)) {
            return $maJohn;
        }

        switch ($typeName) {
            case '日幣':
            case '日圓':
                $money = $sourceData->rates->JPY;
                break;

            case '美元':
            case '美金':
                $money = $sourceData->rates->USD;
                break;

            case '英鎊':
            case '英金':
                $money = $sourceData->rates->GBP;
                break;

            case '印尼盾':
                $money = $sourceData->rates->IDR;
                break;

            case '港幣':
                $money = $sourceData->rates->HKD;
                break;

            case '韓幣':
                $money = $sourceData->rates->KRW;
                break;

            case '澳幣':
                $money = $sourceData->rates->AUD;
                break;

            case '歐元':
                $money = $sourceData->rates->EUR;
                break;

            case '泰銖':
            case '泰珠':
            case '泰豬':
                $money = $sourceData->rates->THB;
                break;

            case '人民幣':
                $money = $sourceData->rates->CNY;
                break;
            default:
                return '';
        }

        //check zero
        if ($money->buySpot == 0) {
            $round = '無資料';
        } else {
            $round = round(1 / $money->buySpot, 4);
        }

        //to string
        $txt = "買入現金 : " . $money->buyCash;
        $txt .= "\n";
        $txt .= "買入即期 : " . $money->buySpot;
        $txt .= "\n";
        $txt .= "賣出現金 : " . $money->sellCash;
        $txt .= "\n";
        $txt .= "賣出即期 : " . $money->sellSpot;
        $txt .= "\n";
        $txt .= "所以買入一台幣 = " . $round . $typeName;
        $txt .= "\n";
        $txt .= "\n";
        $txt .= "懂嗎 孩子？";
        $txt .= "\n";
        $txt .= "就是在講你 !! 一定會問我一台幣等於多少" . $typeName;
        $txt .= "\n";
        $txt .= "\n";
        $txt .= "(╯°□°)╯︵ ┻━┻";
        $txt .= "\n";
        $txt .= "更新時間 : " . Carbon::createFromTimestamp($sourceData->updateTime)->format('Y-m-d H:i:s');

        return $txt;
    }

    public function maJohn($typeName)
    {
        switch ($typeName) {
            default:
                return '';
        }
    }

    public function funny($typeName)
    {
        switch (strtoupper($typeName)) {
            case "老公存款":
                return '八億七千萬';
            case "QUNI":
                return '負二代';
            case "岳群存款":
                return '八七日圓';
            case "RAIDEN":
                return '台灣雲端運算首席專案經理';
            case "540存款":
                return '九四八七日圓';
            case '安安 惠律姐':
                return '幹麻 裝熟？';
            case 540:
                return 487;
            case 487:
                return 540;
            default:
                return '';
        }
    }
}
