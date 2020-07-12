<template>
    <div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                playerId: '',
                song: {},
            }
        },

        mounted() {
            window.onSpotifyWebPlaybackSDKReady = () => {
                const token = this.accessToken;
                console.log(token);
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
                    //@todo make general song entity
                    let song = state.track_window.current_track;
                    if (this.song.name !== song.name) {
                        this.song = song;

                        this.$emit('player_state_changed', {
                            'title' : song.name,
                            'artist_title' : song.artists[0].name,
                            'id' : song.id,
                            'big_image' : song.album.images[0].url
                        });
                    }
                });

                // Ready
                player.addListener('ready', ({ device_id }) => {
                    this.storeDeviceId(device_id).then((response) => {
                        console.log('Ready with Device ID', device_id);
                        this.$emit('player-ready');
                    });
                });

                // Connect to the player!
                player.connect();
            };
        },

        props : {
            'accessToken' : {default: ''},
            'roomName': {default: ''},
            'roomKey' : {default: ''}
        },

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
