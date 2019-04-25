<?php

namespace App\Http\Controllers\Crontab;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\OrderModel;

class CrontabController extends Controller
{
    //定期删除订单表数据
    public function delOrder()
    {
        $time = time();
        $data = OrderModel::where(['is_delete'=>1,'pay_amout'=>0])->get();
        dd($data);
    }
}
