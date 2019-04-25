<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use Illuminate\Support\Str;

class WxController extends Controller
{
    //微信第一次连接测试
    public function valid()
    {
        echo $_GET['echostr'];
    }
    //事件
    public function event()
    {
        //接受服务器推送
        $content = file_get_contents("php://input");
        //写入日志
        $time = date("Y-m-d H:i:s");
        $str = $time . $content . "\n";
        file_put_contents("logs/wx_event.log", $str, FILE_APPEND);

        $data = simplexml_load_string($content);
//        echo "ToUserName:".$data->ToUserName;echo '</br>';      //公众号ID
//        echo "FromUserName:".$data->FromUserName;echo '</br>';  //用户OpenID
//        echo "CreateTime:".$data->CreateTime;echo '</br>';      //时间戳
//        echo "MsgType:".$data->MsgType;echo '</br>';            //消息类型
//        echo "Event:".$data->Event;echo '</br>';                //事件类型
//        echo "Content:".$data->Content;echo '</br>';                //事件类型
//        echo "EventKey:".$data->EventKey;echo '</br>';

        $appid = $data->ToUserName;     //公众号id
        $openid = $data->FromUserName;  //用户OpenId
        $event = $data->Event;          //事件类型
        $MsgType = $data->MsgType;      //素材类型

        if($MsgType=='text'){
            if($data->Content=='最新商品'){
                $goodsUrl = "http://1809sunyujuan.comcto.com/image/share.jpeg";
                $detailUrl = "http://1809sunyujuan.comcto.com/wx/share";
                echo $msg_xml = "<xml>
                        <ToUserName><![CDATA[$openid]]></ToUserName>
                        <FromUserName><![CDATA[$appid]]></FromUserName>
                        <CreateTime>.time()</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                            <item>
                                <Title><![CDATA[最新商品推荐]]></Title>
                                <Description><![CDATA[Apple]]></Description>
                                <PicUrl><![CDATA[$goodsUrl]]></PicUrl>
                                <Url><![CDATA[$detailUrl]]></Url>
                            </item>
                        </Articles>
                        </xml>";
            }
        }



    }
    //分享到朋友圈
    public function share()
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


        $info = [
            'appId' => env('WX_APPID'),             //公众号id
            'timestamp' => $timestamp,              //时间戳
            'nonceStr' => $noncestr,                //随机字符串
            'signature' => $signature,              //签名
            'jsApiList' => ['chooseImage','uploadImage','updateAppMessageShareData','onMenuShareAppMessage']          //使用的JS接口
        ];
        $goods = GoodsModel::get();
        $data = [
            'shareInfo' => $info,
            'goods' => $goods
        ];
        return view('weixin.detail',$data);
    }

    public function getu()
    {
//        echo $_GET;echo '<br>';
        $code = $_GET['code'];
        //获取access——token
        $url1 = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APPID').'&secret='.env('WX_SECRET').'&code='.$code.'&grant_type=authorization_code';
        $response = json_decode(file_get_contents($url1),true);

        $access_token = $response['access_token'];
        $openid = $response['openid'];

        //获取用户信息
        $url2 = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $userInfo = json_decode(file_get_contents($url2),true);
        var_dump($userInfo);

    }




}
