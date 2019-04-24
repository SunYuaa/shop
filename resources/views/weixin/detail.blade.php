<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        @foreach($goods as $k=>$v)
            <tr>
                <td>{{$v->goods_id}}&nbsp;&nbsp;</td>
                <td>{{$v->goods_name}}&nbsp;&nbsp;</td>
                <td>{{$v->goods_price}}&nbsp;&nbsp;</td>
                <td>{{$v->goods_view}}&nbsp;&nbsp;</td>
                <td><a href="/goods/goodsDetail?goods_id={{$v->goods_id}}">商品详情</a></td>
            </tr>
        @endforeach
    </table>
    <button id="share">点击分享</button>


    <script src="/js/jquery/jquery-1.12.4.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
    <script>
        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: "{{$shareInfo['appId']}}", // 必填，公众号的唯一标识
            timestamp: "{{$shareInfo['timestamp']}}", // 必填，生成签名的时间戳
            nonceStr: "{{$shareInfo['nonceStr']}}", // 必填，生成签名的随机串
            signature: "{{$shareInfo['signature']}}",// 必填，签名
            jsApiList: ['updateAppMessageShareData'] // 必填，需要使用的JS接口列表
        });
        wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
            $("#share").click(function(){
                wx.updateAppMessageShareData({
                    title: '商品', // 分享标题
                    desc: '最热', // 分享描述
                    link: 'http://1809sunyujuan.comcto.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: 'http://1809sunyujuan.comcto.com/storage/app/image/1d85bdf0995fcc1fa0519371b7440789.jpeg', // 分享图标
                    success: function (res) {
                        alert(res);
                    }
                })
            })

        });

    </script>
</body>
</html>


