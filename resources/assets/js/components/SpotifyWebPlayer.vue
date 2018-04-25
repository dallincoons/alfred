<template>
    <div>
        <button @click="play">Play</button>
        <button @click="pause">Pause</button>
        <button @click="resume">Resume</button>
        <button @click="next">Next</button>
    </div>
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

                if(this.existingPlayerId) {
                    this.playerId = this.existingPlayerId;
                } else {
                    this.playerId = player._options.id;
                }
                // Error handling
                player.addListener('initialization_error', ({ message }) => { console.error(message); });
                player.addListener('authentication_error', ({ message }) => { console.error(message); });
                player.addListener('account_error', ({ message }) => { console.error(message); });
                player.addListener('playback_error', ({ message }) => { console.error(message); });

                // Playback status updates
                player.addListener('player_state_changed', state => {
                     if (state.paused === true && state.duration === 0) {
                        this.play();
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

        props : {'accessToken' : {default: ''}, 'roomName': {default: ''}, 'roomKey' : {default: ''}, 'existingPlayerId' : {default: ''}},

        methods : {
            play() {
                axios.put(`/room/${this.roomKey}/device/${this.playerId}/play`);
            },

            pause() {
                axios.put(`/room/${this.roomKey}/pause`, {'device_id' : this.playerId});
            },

            resume() {
                axios.put(`/room/${this.roomKey}/resume`, {'device_id' : this.playerId});
            },

            next() {
                axios.put(`/room/${this.roomKey}/next`, {'device_id' : this.playerId});
            },

            storeDeviceId(deviceId) {
                return axios.post(`/room/${this.roomKey}/device`, {'device_id' : deviceId})
            }
        }
    }
</script>
