<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\CartModel;
use App\Model\GoodsModel;
use App\Model\OrderModel;
use App\Model\OrderDetailModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * 创建订单
     */
    public function create()
    {
        //计算总价
        $where = [
            'uid' => Auth::id(),
            'session_id' => Session::getId()
        ];
        $cartInfo = CartModel::where($where)->get();
        $price = 0;
        foreach($cartInfo as $k=>$v){
            $g = GoodsModel::where(['goods_id'=>$v['goods_id']])->first()->toArray();
            $price += $g['goods_price'];
            $total_price = $price / 100;
            $goods[] =$g;
        }
        //写入订单表
        $orderData = [
            'uid' => Auth::id(),
            'order_sn' => OrderModel::getOrderSn(Auth::id()),
            'order_amout' => $total_price,
            'add_time' => time()
        ];
        $oid = OrderModel::insertGetId($orderData);
        //写入详情表
        foreach($goods as $k=>$v){
            $detailData = [
                'oid' => $oid,
                'uid' => Auth::id(),
                'goods_id' => $v['goods_id'],
                'goods_name' => $v['goods_name'],
                'goods_price' => $v['goods_price']
            ];
        }
        $res = OrderDetailModel::insertGetId($detailData);
        if($oid && $res ){
            header('Refresh:2;url=/order/list');
            echo '创建订单成功,跳转至订单列表';
        }else{
            echo '创建订单失败';
        }

    }

    /**
     * 订单列表
     */
    public function orderList()
    {
        $orderList = OrderModel::where(['uid'=>Auth::id()])->get();
        return view('order.list',['orderList'=>$orderList]);
    }

    /**
     * 查询订单是否完成支付
     */
    public function payStatus()
    {
        $oid = intval($_GET['oid']);
        $order = OrderModel::where(['oid'=>$oid])->first();
        $response = [];
        if($order){
            if($order->pay_time > 0){      //已支付
                $response = [
                    'status' => 0 ,        //1 已支付
                    'msg' => 'ok'
                ];
            }
//            print_r($order->toArray());
        }else{
            die('订单不存在');
        }
        die(json_encode($response));
    }

}
