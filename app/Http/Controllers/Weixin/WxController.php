<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use App\Model\WxuserModel;
use Illuminate\Support\Str;
use App\Model\TmpWxuserModel;

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

        if($MsgType=='event'){
            if($event=='subscribe'){
                //首次关注 推送图文 用户信息入库
                if(isset($data->EventKey)){
                    $qrscene = explode('_',$data->EventKey)[1];
                    $user = $this->getUserInfo($openid);
                    $info = [
                        'openid' => $user['openid'],
                        'nickname' => $user['nickname'],
                        'sex' => $user['sex'],
                        'city' => $user['city'],
                        'province' => $user['province'],
                        'country' => $user['country'],
                        'headimgurl' => $user['headimgurl'],
                        'create_time' => $user['subscribe_time'],
                        'qrscene_id' => $qrscene
                    ];
                    $id = TmpWxuserModel::insertGetId($info);
                    if($id){
                        echo $msg_xml = "<xml>
                        <ToUserName><![CDATA[$openid]]></ToUserName>
                        <FromUserName><![CDATA[$appid]]></FromUserName>
                        <CreateTime>.time()</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                            <item>
                                <Title><![CDATA[欢迎关注^^]]></Title>
                                <Description><![CDATA[Apple]]></Description>
                                <PicUrl><![CDATA[http://1809sunyujuan.comcto.com/image/share.jpeg]]></PicUrl>
                                <Url><![CDATA[http://1809sunyujuan.comcto.com/wx/share]]></Url>
                            </item>
                        </Articles>
                        </xml>";
                    }
                }

            }elseif($event=='SCAN'){
                //关注后扫码 推送图文
                echo $msg_xml = "<xml>
                        <ToUserName><![CDATA[$openid]]></ToUserName>
                        <FromUserName><![CDATA[$appid]]></FromUserName>
                        <CreateTime>.time()</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                            <item>
                                <Title><![CDATA[欢迎回来~]]></Title>
                                <Description><![CDATA[Apple]]></Description>
                                <PicUrl><![CDATA[http://1809sunyujuan.comcto.com/image/share.jpeg]]></PicUrl>
                                <Url><![CDATA[http://1809sunyujuan.comcto.com/wx/share]]></Url>
                            </item>
                        </Articles>
                        </xml>";
            }

        }

        //发送‘最新商品’推送图文
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
    //根据openid获取用户信息
    public function getUserInfo($openid){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.getWxAccessToken().'&openid='.$openid.'&lang=zh_CN';
        $res = json_decode(file_get_contents($url),true);
        return $res;
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

    //微信网页授权
    public function getu()
    {
        $code = $_GET['code'];
        //获取access——token
        $url1 = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APPID').'&secret='.env('WX_SECRET').'&code='.$code.'&grant_type=authorization_code';
        $response = json_decode(file_get_contents($url1),true);

        $access_token = $response['access_token'];
        $openid = $response['openid'];

        //获取用户信息
        $url2 = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $userInfo = json_decode(file_get_contents($url2),true);
//        var_dump($userInfo);
        //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx80fc97799f2a0754&redirect_uri=https%3A%2F%2F1809sunyujuan.comcto.com%2Fwxweb%2Fu&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
        //array(9) {
        // ["openid"]=> string(28) "o3RJO1E2RG6hCCXm0Vt6PvUvLdtY"
        // ["nickname"]=> string(6) "sakura"
        // ["sex"]=> int(2)
        // ["language"]=> string(5) "zh_CN"
        // ["city"]=> string(0) ""
        // ["province"]=> string(0) ""
        // ["country"]=> string(6) "中国"
        // ["headimgurl"]=> string(129) "http://thirdwx.qlogo.cn/mmopen/vi_32/zV0F7K44vHic2CF3hgtSBxRhm1CI903R98079SeKooP5NFicaDNCckeE9GQqsImtVBOcWAmng1dR57C2bYNxQ19g/132"
        // ["privilege"]=> array(0) { } }

        //用户信息入库
        $openid = $userInfo['openid'];
        $open = WxuserModel::where(['openid'=>$openid])->first();
        if($open){
            echo '欢迎回来~'.$openid;
        }else{
            $info = [
                'openid' => $userInfo['openid'],
                'nickname' => $userInfo['nickname'],
                'sex' => $userInfo['sex'],
                'city' => $userInfo['city'],
                'province' => $userInfo['province'],
                'country' => $userInfo['country'],
                'headimgurl' => $userInfo['headimgurl'],
            ];
            WxuserModel::insertGetId($info);
            echo '欢迎关注``'.$openid;
        }
    }



}
