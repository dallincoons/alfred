<template>
    <button @click="play">Play</button>
</template>

<script>
    export default {
        data() {
            return {
                playerId: ''
            }
        },

        created() {
            window.onSpotifyWebPlaybackSDKReady = () => {
                const token = this.accessToken;
                const player = new Spotify.Player({
                    name: this.roomName,
                    getOAuthToken: cb => { cb(token); }
                });

                this.playerId = player._options.id;

                // Error handling
                player.addListener('initialization_error', ({ message }) => { console.error(message); });
                player.addListener('authentication_error', ({ message }) => { console.error(message); });
                player.addListener('account_error', ({ message }) => { console.error(message); });
                player.addListener('playback_error', ({ message }) => { console.error(message); });

                // Playback status updates
                player.addListener('player_state_changed', state => {
                    console.log(state);
                     if (state.paused === true && state.duration === 0) {
                        this.play();
                     }
                });

                // Ready
                player.addListener('ready', ({ device_id }) => {
                    console.log('Ready with Device ID', device_id);
                });

                // Connect to the player!
                player.connect();
            };
        },

        props : ['accessToken', 'roomName', 'roomKey'],

        methods : {
            play() {
                axios.put(`/room/${this.roomKey}/device/${this.playerId}/play`)
            }
        }
    }
</script>
