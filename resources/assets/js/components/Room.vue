<template>
    <div>
        <h1>{{name}} - <span>{{code}}</span></h1>

        <span>Add Song</span>
        <input type="text" v-model="songName"/>
        <button @click="searchSongs(songName)">Search</button>

        <button @click="play">Play</button>
        <button @click="pause">Pause</button>
        <button @click="resume">Resume</button>
        <button @click="next">Next</button>

        <spotify-web-player v-if="!has_parent || !existing_player_id"
            :accessToken="access_token"
            :roomName="name"
            :roomKey="rkey"
            @deviceId="storePlayerId"
            @next="next"
            ></spotify-web-player>

        <div v-for="song in songs">
            <div v-for="item in song.items">
                <span @click="addSong(rkey, item)">{{item.name}} - {{ item.album.artists[0].name }}</span>
            </div>
        </div>
    </div>
</template>

<script>
    import SpotifyWebPlayer from '../components/SpotifyWebPlayer';

    export default {
        components : {
            SpotifyWebPlayer
        },

        data() {
            return {
                songName: '',
                songs: [],
                playerId: ''
            }
        },

        props : [
            'name',
            'rkey',
            'code',
            'access_token',
            'existing_player_id',
            'has_parent'
        ],

        created() {
            if(this.existing_player_id) {
                this.storePlayerId(this.existing_player_id);
            }

            Echo.channel(`song-queue`)
                .listen('SongQueueStarted', (e) => {
                    console.log(e);
                });
        },

        methods : {
           searchSongs(song) {
               axios.get('/spotify/songs?q=' + song).then((response) => {
                   this.songs = response.data;
               });
           },

           addSong(room, song) {
               axios.post('/room/' + room + '/song', {song: song}).then((response) => {
                   alert('success');
               });
           },

            play() {
                axios.put(`/room/${this.rkey}/device/${this.playerId}/play`);
            },

            pause() {
                axios.put(`/room/${this.rkey}/pause`, {'device_id' : this.playerId});
            },

            resume() {
                axios.put(`/room/${this.rkey}/resume`, {'device_id' : this.playerId});
            },

            next() {
                axios.put(`/room/${this.rkey}/next`, {'device_id' : this.playerId});
            },

            storePlayerId(deviceId) {
               this.playerId = deviceId;
            }
        }
    }
</script>
