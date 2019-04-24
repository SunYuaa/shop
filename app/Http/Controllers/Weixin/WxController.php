<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\GoodsModel;

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
                $goodsUrl = "http://1809sunyujuan.comcto.com/storage/app/image/1d85bdf0995fcc1fa0519371b7440789.jpeg";
                $detailUrl = "http://1809sunyujuan.comcto.com/wx/goodsDetail";
                echo $msg_xml = "<xml>
                        <ToUserName><![CDATA[$openid]]></ToUserName>
                        <FromUserName><![CDATA[$appid]]></FromUserName>
                        <CreateTime>.time()</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                            <item>
                                <Title><![CDATA[最新商品推荐]]></Title>
                                <Description><![CDATA[Aplle系列]></Description>
                                <PicUrl><![CDATA[$detailUrl]]></PicUrl>
                                <Url><![CDATA[$detailUrl]]></Url>
                            </item>
                        </Articles>
                        </xml>";
            }

        }



    }

    //
    public function goodsDetail()
    {
        echo 'hha';die;
        if($goods_id) {
            $data = GoodsModel::get();
            if (!$data) {
                die('商品不存在');
            }
            $data = [
                'goods' => $data
            ];
            return view('weixin.detail',$data);
        }
    }


}
