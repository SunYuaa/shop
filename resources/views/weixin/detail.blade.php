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
                <td><img src="/image/{{$k}}.jpg" alt="" width="100"></td>
            </tr>
        @endforeach
    </table>


    <script src="/js/jquery/jquery-1.12.4.min.js"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
    <script>

        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: "{{$shareInfo['appId']}}", // 必填，公众号的唯一标识
            timestamp: "{{$shareInfo['timestamp']}}", // 必填，生成签名的时间戳
            nonceStr: "{{$shareInfo['nonceStr']}}", // 必填，生成签名的随机串
            signature: "{{$shareInfo['signature']}}",// 必填，签名
            jsApiList: ['chooseImage','uploadImage','updateAppMessageShareData','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表

        });
        wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
            wx.updateAppMessageShareData({
                title: '最新商品', // 分享标题
                desc: 'Apple系', // 分享描述
                link: 'http://1809sunyujuan.comcto.com/wx/share', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://1809sunyujuan.comcto.com/image/share.jpeg', // 分享图标
                success: function () {
//                alert('分享成功');
                }
            });

            wx.onMenuShareAppMessage({
                title: '最新商品', // 分享标题
                desc: 'Apple列', // 分享描述
                link: 'http://1809sunyujuan.comcto.com/wx/share', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://1809sunyujuan.comcto.com/image/share.jpeg', // 分享图标
                type: '', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                    // 用户点击了分享后执行的回调函数
                    alert('分享成功');
                }
            });

        });

    </script>
</body>
</html>



