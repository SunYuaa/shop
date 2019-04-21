<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderModel extends Model
{
    //
    protected $table = 's_order';
    public $timestamps = false;

    //生成订单编号
    public static function getOrderSn($uid){
        $str = time().rand(1111,9999).Str::random(16);
        $order_sn = substr(md5($str),16);
        return $order_sn;
    }
}
