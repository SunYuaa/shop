<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

    function test(){
    echo 'helper';
}
//根据openid获取用户信息
    function getUserInfo($openid){
    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.getWxAccessToken().'&openid='.$openid.'&lang=zh_CN';
    $res = json_decode(file_get_contents($url),true);
    return $res;
    }
//生成带参数的二维码
    function tmp()
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
    return $ticket;
}
/**
 * 获取access_token
 * @return bool
 */
    function getWxAccessToken()
{
    $key = 'wx_access_token';     //shop_wx_access_token
    $access_token = Redis::get($key);
    if($access_token){
        return $access_token;
    }else{
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_SECRET');
        $response = json_decode(file_get_contents($url),true);

        if(isset($response['access_token'])){
            Redis::set($key,$response['access_token']);
            Redis::expire($key,3600);
            return $response['access_token'];
        }else{
            return false;
        }
    }


}

/**
 * 获取jsapi_ticket
 * @return mixed
 */
    function getJsapiTicket()
    {
        $key = 'wx_jsapi_ticket';   //shop_wx_jsapi_ticket
        $ticket = Redis::get($key);
        if($ticket){
            return $ticket;
        }else{
            $access_token = getWxAccessToken();
            $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
            $ticket_info = json_decode(file_get_contents($url),true);
            if(isset($ticket_info['ticket'])){
                Redis::set($key,$ticket_info['ticket']);
                Redis::expire($key,3600);
                return $ticket_info['ticket'];
            }else{
                die('获取签名出错');
            }
        }
    }