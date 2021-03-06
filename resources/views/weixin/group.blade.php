<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table{
            border:1px solid black;
            border-collapse:collapse;
        }
    </style>
</head>
<body>
<table border="1" style="margin-bottom: 30px">
    <tr>
        <td><input type="checkbox" id="allbox"></td>
        <td width="50" align="center">id</td>
        <td width="250" align="center">openid</td>
        <td width="250" align="center">昵称</td>
    </tr>
    @foreach($user as $k=>$v)
        <tr>
            <td openid="{{$v->openid}}"><input type="checkbox" class="box"></td>
            <td align="center">{{$v->id}}</td>
            <td align="center">{{$v->openid}}</td>
            <td align="center">{{$v->nickname}}</td>
        </tr>
    @endforeach
</table>

<input type="text" placeholder="请填写要发送的内容" id="text">
<button id="btn">发送</button>
</body>
</html>
<script src="/js/jquery/jquery-1.12.4.min.js"></script>
<script>
    $(function () {
        //全选
        $('#allbox').click(function () {
            var checked=$(this).prop('checked');
            $('.box').prop('checked',checked);
        })
        
        //点击发送
        $('#btn').click(function () {
            var id=$('.box');
            var text=$('#text').val();
            var openid='';
            id.each(function (res) {
                if ($(this).prop('checked')==true){
                    openid+=$(this).parent('td').attr('openid')+',';
                }
            })
            openid=openid.substr(0,openid.length-1);
            if(openid==''){
                alert('请选择要发送的人');
                return false;
            }
            if(text==''){
                alert('请输入发送的内容');
                return false;
            }
            $.ajax({
                url:'/admin/groups/group?openid='+openid+'&text='+text,
                type:'get'
            })
        })
    })
</script>
