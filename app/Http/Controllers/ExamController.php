<?php

namespace App\Http\Controllers;

use App\Model\WxuserModel;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use phpDocumentor\Reflection\File;

class ExamController extends Controller
{
    //网页授权
    public function web()
    {
        $url = urlencode('http://1809sunyujuan.comcto.com/exam/web');

        $code = $_GET['code'];
        //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx80fc97799f2a0754&redirect_uri=http%3A%2F%2F1809sunyujuan.comcto.com%2Fexam%2Fweb&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect

        $acc_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APPID').'&secret='.env('WX_SECRET').'&code='.$code.'&grant_type=authorization_code';
        $response = json_decode(file_get_contents($acc_url),true);

        $access_token = $response['access_token'];
        echo $access_token;
     }

    //创建标签
    public function createTag()
    {
        $access_token = getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$access_token;
        $client = new Client();
        $json_arr = [
            'tag' => [
                'name' => 'bird'
            ]
        ];
        $response = $client->request('post',$url,[
            'body' => json_encode($json_arr,JSON_UNESCAPED_UNICODE)
        ]);
        $str = $response->getBody();
        $back = json_decode($str,true);
        print_r($back);
    }
    //获取已创建标签  为用户加标签
    public function getTag()
    {
        $access_token = getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$access_token;
        $response = json_decode(file_get_contents($url),true);
        print_r($response);echo '<hr/>';


        //为用户加标签
        $url2 = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.$access_token;
        $client = new Client();
        $user_openid = WxuserModel::get('openid')->toArray();
//        dump($user_openid);die;
        $openid1 = $user_openid[0]['openid'];
        $openid2 = $user_openid[1]['openid'];
        $openid3 = $user_openid[2]['openid'];
        $openid4 = $user_openid[3]['openid'];

        $user = [
            "openid_list" => [//粉丝列表
                $openid3,
                $openid4
            ],
            "tagid" => 102
        ];
        $body = $client->request('post',$url2,[
            'body' => json_encode($user,JSON_UNESCAPED_UNICODE)
        ]);
        $res = json_decode($body->getBody());
        dump($res);

    }
    //获取粉丝下标签列表
    public function tagList()
    {
        $access_token = getWxAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token='.$access_token;

        $client = new Client();
        $json_arr = [
            'tagid' => 101,
            'next_openid' => ''
        ];
        $response = $client->request('post',$url,[
            'body' => json_encode($json_arr,JSON_UNESCAPED_UNICODE)
        ]);
        $info = json_decode($response->getBody(),true);

        $data = [
            'info' => $info['data']['openid']
        ];
        return view('exam.tagList',$data);
    }
    //群发消息
    public function group(){
        $client=new Client();
        $openid=$_GET['openid'];
        $text=$_GET['text'];

        $openid=explode(',',$openid);
        $arr=[
            'touser' => $openid,
            'msgtype' => 'text',
            'text' => [
                'content'=>$text
            ]
        ];
        $str=json_encode($arr,JSON_UNESCAPED_UNICODE);

        $url='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.getWxAccessToken();
        $response=$client->request('POST',$url,[
            'body'=>$str
        ]);
        $body = response->getBody();
        if($body){
            alert('发送成功');
        }else{
            alert('发送失败');
        }

    }
}
