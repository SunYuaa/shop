<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class TmpController extends Controller
{
    //生成带参数的二维码
    public function tmp()
    {
        $client = new Client();
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.getWxAccessToken();
        $data = [
            "expire_seconds" => 604800,
            "action_name" => "QR_SCENE",
            "action_info" => [
                "scene" => [
                    "scene_id" => 123
                ]
            ]
        ];
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);
        $response =$client->request('post',$url,[
            'body' => $json
        ]);
        $res = json_decode($response->getBody(),true);

        $ticket = $res['ticket'];
        $url2 = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;

        var_dump($url2);
    }

    //扫码
    public function event()
    {
        echo '11111';die;
        //接受服务器推送
        $content = file_get_contents("php://input");
        //写入日志
        $time = date("Y-m-d H:i:s");
        $str = $time . $content . "\n";
        file_put_contents("logs/wx_tmp.log", $str, FILE_APPEND);

        $data = simplexml_load_string($content);
        var_dump($data);
    }
}
