<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class JssdkController extends Controller
{
    //jssdk测试
    public function jsTest()
    {
        //计算签名
        $noncestr = Str::random(10);
        $ticket = getJsapiTicket();
        $timestamp = time();
        $current_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];  //http://www.shop.com.wx/js/jssdk
//        echo 'noncestr:'.$noncestr;echo '<br/>';
//        echo 'ticket:'.$ticket;echo '<br/>';
//        echo 'timestamp:'.$timestamp;echo '<br/>';
//        echo 'current_url:'.$current_url;echo '</br>';
        $string1 = "jsapi_ticket=$ticket&noncestr=$noncestr&timestamp=$timestamp&url=$current_url";
        $signature = sha1($string1);


        $js_config = [
            'appId' => env('WX_APPID'),             //公众号id
            'timestamp' => $timestamp,              //时间戳
            'nonceStr' => $noncestr,                //随机字符串
            'signature' => $signature,              //签名
            'jsApiList' => ['chooseImage']          //使用的JS接口
        ];
        $data = [
            'jsconfig' => $js_config
        ];
        return view('weixin.jssdk',$data);


    }

    public function getImg()
    {
        dump($_GET);
    }
}
