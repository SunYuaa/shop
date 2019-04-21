<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\GoodsModel;
use App\Model\CartModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * 购物车首页
     */
    public function index()
    {
        echo '购物车首页';echo '<hr>';
        echo __METHOD__;echo '<hr>';

        //购物车列表
        $where = [
            'uid' => Auth::id(),
            'session_id' => Session::getId()
        ];
        $cartList = CartModel::where($where)->get();
        if($cartList){
            $total_price = 0;
            foreach($cartList as $k=>$v){
                $g = GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
                $total_price += $g['goods_price'];
                $goodsList[] = $g;
            }
            $data = [
                'goodsList' => $goodsList,
                'total'     => $total_price / 100
            ];
            //购物车列表视图
            return view('cart.index',$data);
        }else{
            header('Refresh:3;url=/');
            echo '购物车为空,3秒后跳转至首页';
        }
    }
    /**
     * 添加至购物车
     */
    public function add($goods_id=0)
    {
        if(empty($goods_id)){
            header('Refresh:3;url=/cart');
            die('请选择商品，3秒后跳转至购物车');
        }
        //判断商品是否有效  存在》未下架》未删除
        $goods = GoodsModel::where(['goods_id'=>$goods_id])->first();
        if($goods){  //已存在
            if($goods->is_delete==2){
                header('Refresh:3;url=/cart');
                die('商品已删除，3秒后跳转至购物车');
            }
            //添加购物车
            $cartData = [
                'goods_id' => $goods_id,
                'uid' => Auth::id(),
                'add_time' => time(),
                'session_id' => Session::getId()
            ];
            $res = CartModel::insertGetId($cartData);
            if($res){
                header('Refresh:3;url=/cart');
                die('购物车添加成功，3秒后跳转至购物车列表');
            }else{
                header('Refresh:3;url=/');
                die('购物车添加失败');
            }
        }else{
            die('商品不存在');
        }

    }
}
