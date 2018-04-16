<template>
    <button @click="play">Play</button>
</template>

<script>
    export default {
        created() {
            window.onSpotifyWebPlaybackSDKReady = () => {
                const token = this.access_token;
                const player = new Spotify.Player({
                    name: 'caca8000',
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
        },

        props : ['access_token'],

        methods : {
            play() {
                // axios.put().then(response => {
                //
                // })
            }
        }
    }
</script>
