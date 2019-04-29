<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\GoodsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class GoodsController extends Controller
{
    //商品列表
    public function goodsList()
    {
        $goodsList = GoodsModel::get();
        $data = [
            'goodsList' => $goodsList,
        ];
        return view('goods.goodsList',$data);
    }

    //商品详情 浏览次数
//    public function goodsDetail()
//    {
//        $goods_id = $_GET['goods_id'];
//
//        $key = 'goods_view_count'.$goods_id;
//        Redis::incr($key,1);
//        $goods_view_count = Redis::get($key);
//
//        GoodsModel::where(['goods_id'=>$goods_id])->update(['goods_view'=>$goods_view_count]);
//        $goods = GoodsModel::where(['goods_id'=>$goods_id])->first();
//
//        if($goods){
//            echo 'cache';
//        }else{
//            echo 'Nocache';
//            $goods_view_count = $goods->goods_view;
//        }
//
//        $data = [
//            'goods' => $goods,
//            'goods_view_count' => $goods_view_count
//        ];
//        return view('goods.goodsDetail',$data);
//    }

    //浏览次数排序
    public function getSort()
    {
        $ss_key = 'ss:goods:sort';

        $list1 = Redis::zRangeByScore($ss_key,0,10000,['withscores'=>true]);//正序
        print_r($list1);echo '<br/>';
        $list2 = Redis::zRevRange($ss_key,0,10000,true);//倒序
        print_r($list2);
    }

    //商品详情页
    public function goodsDetail(){
        $goods_id=$_GET['goods_id'];

        if($goods_id){
            $data=GoodsModel::where(['goods_id'=>$goods_id])->first();
            if(!$data){
                die('商品不存在');
            }
            $key='see_num'.$goods_id;
            //redis自增
            Redis::incr($key);
            $see_num=Redis::get($key);
            $sort_key='ss:goods';
            //redis储存有序集合
            Redis::zAdd($sort_key,$see_num,$goods_id);
            //浏览历史
            $h_key='ss:history';
            Redis::zAdd($h_key,time(),$goods_id);
            //获取商品排序信息
            $goods=$this->getSeeSort();
            //获取浏览记录
            $history=$this->history();

            //二维码
            $server=['server'=>$_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] .$_SERVER['REQUEST_URI']];
            $url_code = "http://1809sunyujuan.comcto.com//goods/goodsDetail/".$goods_id;
            $data=[
                'data'=>$data,
                'see_num'=>$see_num,
                'goods'=>$goods,
                'history'=>$history,
                'url_code'=>$url_code
            ];
        }
        return view('goods.goodsDetail',$data,$server);
    }

    //获取商品浏览量排行
    public function getSeeSort(){
        $sort_key='ss:goods';
        $arr=Redis::zRevRange($sort_key,0,10000,true);
        $goods_id=array_keys($arr);
        //print_r($goods_id);
        $data=[];
        foreach($goods_id as $k=>$v){
            $goods=GoodsModel::where(['goods_id'=>$v])->first()->toArray();
            $data[]=$goods;
        }
        //print_r($data);
        return $data;
    }
    //获取浏览记录
    public function history(){
        $h_key='ss:history';
        $arr=Redis::zRevRange($h_key,0,1999999999,true);
        $goods_id=array_keys($arr);
        //print_r($goods_id);
        $data=[];
        foreach($goods_id as $k=>$v){
            $goods=GoodsModel::where(['goods_id'=>$v])->first()->toArray();
            $data[]=$goods;
        }
        //print_r($data);
        return $data;
    }




}
