<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>4.19shop</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 30px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">

                    <table class="title m-b-md">
                        <tr>
                            <td>{{$data->goods_id}}&nbsp;&nbsp;</td>
                            <td>{{$data->goods_name}}&nbsp;&nbsp;</td>
                            <td>{{$data->goods_price}}&nbsp;&nbsp;</td>
                            <td>{{$data->goods_store}}&nbsp;&nbsp;</td>
                            <td>商品浏览次数：{{$see_num}} </td>
                        </tr>
                    </table>
                    <h3>最热：</h3> <br>
                    <ol>
                        @foreach($goods as $k=>$v)
                            <li>
                                <h3>{{$v['goods_name']}}</h3>
                            </li>
                        @endforeach
                    </ol>
                    <hr>
                    <h3>浏览记录：</h3> <br>
                    <ol>
                        @foreach($history as $k=>$v)
                            <li>
                                <h3>{{$v['goods_name']}}</h3>
                            </li>
                        @endforeach
                    </ol>
            </div>
        </div>
    </body>
</html>
