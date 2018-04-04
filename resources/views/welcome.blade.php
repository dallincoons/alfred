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
            @else
                <span>Welcome, {{\Auth::user()->name}}</span> | <span><a href="/logout">Logout</a></span>
            @endif
        </div>
        <script src="https://sdk.scdn.co/spotify-player.js"></script>
        <script>
            window.onSpotifyWebPlaybackSDKReady = () => {
                const token = 'BQCtFjZITlqSh1IZ_vgyv7VsrSg8S5Fmdn6431CA3QyX-IFMHrTsOyLM5SJ3obm5F4avwlCOTHAvqFUhpqn05v_sHnPotl3F16dSpyRrmCg0ZkWF2gJ6JX-KHH13VMvOV7OVQ0TBZ1e9fqyCmZOqIQcZ4KFsnl5bviREIw';
                const player = new Spotify.Player({
                    name: 'Web Playback SDK Quick Start Player',
                    getOAuthToken: cb => { cb(token); }
                });

                // Error handling
                player.addListener('initialization_error', ({ message }) => { console.error(message); });
                player.addListener('authentication_error', ({ message }) => { console.error(message); });
                player.addListener('account_error', ({ message }) => { console.error(message); });
                player.addListener('playback_error', ({ message }) => { console.error(message); });

                // Playback status updates
                player.addListener('player_state_changed', state => { console.log(state); });

                // Ready
                player.addListener('ready', ({ device_id }) => {
                    console.log('Ready with Device ID', device_id);
                });

                // Connect to the player!
                player.connect();
            };
        </script>
    </body>
</html>
