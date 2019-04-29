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

}
