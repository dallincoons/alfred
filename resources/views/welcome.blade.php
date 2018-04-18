<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        {{--<iframe src="https://open.spotify.com/embed?uri=spotify%3Atrack%3A33Q6ldVXuJyQmqs8BmAa0k" width="250" height="330" frameborder="0" allowtransparency="true"></iframe>--}}

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
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
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            @if(!\Auth::check())
                <a href="/connect">Connect</a>

                <form action="room/join" method="POST">
                    {{ csrf_field() }}
                    <h2>Join Room</h2>
                    <input name="room" style="text-transform:uppercase"/>
                    <input type="submit" />
                </form>
            @else
                <span>Welcome, {{\Auth::user()->name}}</span> | <span><a href="/logout">Logout</a></span>

                <h2>Create Room</h2>
                <form action="rooms" method="POST">
                    {{ csrf_field() }}
                    <input name="room_name" />
                    <button>Save</button>
                </form>

            @endif
        </div>

        <div id="container">

        </div>
    </body>
</html>
