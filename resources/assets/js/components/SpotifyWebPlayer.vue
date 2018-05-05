<template>
    <div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                playerId: ''
            }
        },

        mounted() {
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
                     if (state.paused === true && state.position === 0) {
                        this.next();
                     }
                });

                // Ready
                player.addListener('ready', ({ device_id }) => {
                    this.storeDeviceId(device_id).then((response) => {
                        console.log('Ready with Device ID', device_id);
                    });
                });

                // Connect to the player!
                player.connect();
            };
        },

        props : {'accessToken' : {default: ''}, 'roomName': {default: ''}, 'roomKey' : {default: ''}},

        methods : {
            storeDeviceId(deviceId) {
                this.$emit('deviceId', deviceId);
                return axios.post(`/room/${this.roomKey}/device`, {'device_id' : deviceId})
            },

            next() {
                this.$emit('next');
            }
        }
    }
</script>
