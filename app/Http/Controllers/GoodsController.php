<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\GoodsModel;
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

    //商品详情
    public function goodsDetail()
    {
        $goods_id = $_GET['goods_id'];

        $key = 'goods_view_count'.$goods_id;
        $res = GoodsModel::get()->toArray();
        foreach($res as $k=>$v){
            if($goods_id==$v['goods_id']){
                Redis::incr($key,1);
                $goods_view_count = Redis::get($key);

                GoodsModel::where(['goods_id'=>$v['goods_id']])->update(['goods_view'=>$goods_view_count]);
                $goods = GoodsModel::where(['goods_id'=>$v['goods_id']])->first();
            }
        }
        if($goods){
            echo 'cache';
        }else{
            echo 'Nocache';
            $goods_view_count = $goods->goods_view;
        }

        $data = [
            'goods' => $goods,
            'goods_view_count' => $goods_view_count
        ];
        return view('goods.goodsDetail',$data);
    }
}
