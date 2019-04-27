<?php

namespace App\Admin\Controllers;

use App\Model\WxuserModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use GuzzleHttp\Client;

class WxuserController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $user = WxuserModel::get();
        return $content
            ->header('用户管理')
            ->description('群发消息')
            ->body(view('weixin.group',['user'=>$user]));
    }
    //群发消息
    public function groups(){
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
//        print_r($arr);die;
        $str=json_encode($arr,JSON_UNESCAPED_UNICODE);

        $url='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.getWxAccessToken();
        $response=$client->request('POST',$url,[
            'body'=>$str
        ]);
        print_r($response->getBody());
    }

//    protected function grid()
//    {
//        $grid = new Grid(new WxuserModel);
//
//        $grid->id('ID');
//        $grid->openid('OpenId');
//        $grid->nickname('用户昵称');
//        $grid->sex('性别')->using(['2' => '女', '1' => '男']);
//        $grid->headimgurl('头像')->image();
//        $grid->subscribe_time('添加时间')->display(function($subscribe_time){
//            return date('Y-m-d H:i:s',$subscribe_time);
//        });
//        $grid->sub_status('是否关注')->using(['1' => '是', '2' => '否']);
//
//        return $grid;
//    }


}
