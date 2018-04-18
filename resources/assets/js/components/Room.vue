<template>
    <div>
        <h1>{{name}} - <span>{{code}}</span></h1>

        <span>Add Song</span>
        <input type="text" v-model="songName"/>
        <button @click="searchSongs(songName)">Search</button>

        <div v-for="song in songs">
            <div v-for="item in song.items">
                <span @click="addSong(rkey, item.id)">{{item.name}} - {{ item.album.artists[0].name }}</span>
            </div>
        </div>
        <spotify-web-player :accessToken="access_token" :roomName="name" :roomKey="rkey"></spotify-web-player>
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
                songs: []
            }
        },

        props : [
            'name',
            'rkey',
            'code',
            'access_token'
        ],

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
    }
</script>
