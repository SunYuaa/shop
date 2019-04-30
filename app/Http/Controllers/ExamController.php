<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamController extends Controller
{
    //网页授权
    public function web()
    {
        $url = urlencode('http://1809sunyujuan.comcto.com/exam/web');

        $code = $_GET['code'];
        echo $code;
        //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxf0e81c3bee622d60&redirect_uri=http%3A%2F%2F1809sunyujuan.comcto.com%2Fexam%2Fweb&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
     }
}
