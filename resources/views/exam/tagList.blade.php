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
<table border="1px solid red">
    <tr>
        <td><input type="checkbox" id='allbox'></td>
        <td>标签</td>
        <td>OPENID</td>
    </tr>
    @foreach ($info as $k => $v)
    <tr>
        <td><input type="checkbox" class="box"></td>
        <td>apple</td>
        <td class='openid'>{{$v}}</td>
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
                    openid+=$(this).parent('td').next().next().text()+',';
                }
            })
            openid=openid.substr(0,openid.length-1);
            console.log(openid);
            if(openid==''){
                alert('请选择要发送的人');
                return false;
            }
            if(text==''){
                alert('请输入发送的内容');
                return false;
            }
            $.ajax({
                url:'/exam/group?openid='+openid+'&text='+text,
                type:'get'
            })
        })
    })
</script>