<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Alfred') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://sdk.scdn.co/spotify-player.js"></script>
<script>
    window.onSpotifyWebPlaybackSDKReady = () => {
        const token = 'BQDgq-s4OBsCQlsVdFS6LkqVikwKKfNuffdxhYaRqWKMQbI3R2vympQMwEtyo4N4GdlKwG1b5EAfWpMOb61cyP-hNwZo7bFHaB0kePZMvQsrmPvyd5bvIFa1ZnmnyeLIF6oNlmDOd5SRztfEugoMvbSuK6htB36viF21JrJU0QvvEVcIKYyD8HSgpjAgjo8fnJqIHBPdO4NVQgBBgKe_6EBmpjRMPu6g7Xos94oC3Uhveiqh'
        const player = new Spotify.Player({
            name: 'caca2',
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
<script>
    window.axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    new Vue({
        el : '#app',

        data() {
            return {
                songName: '',
                songs: []
            }
        },

        methods : {
           searchSongs(song) {
               axios.get('/spotify/songs?q=' + song).then((response) => {
                   this.songs = response.data;
               });
           },

           addSong(room, song) {
               axios.post('/room/' + room + '/song/' + song).then((response) => {
                   alert('success');
               });
           }
        }
    });
</script>
</body>
</html>
