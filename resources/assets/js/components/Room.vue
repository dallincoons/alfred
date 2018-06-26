<template>
    <div class="main-wrapper bg-gradient h-screen">
        <div class="room-heading">
            <h2 class="room-code">{{code}}</h2>
            <div class="search-section">
                <div class="search-icon" @click="searchInputVisible = !searchInputVisible">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 56.97"><title>search</title><path id="search" data-name="search" d="M54.16,51.89,40.6,37.79a23,23,0,1,0-4.42,4.05L49.84,56.05a3,3,0,0,0,4.32-4.16ZM23,6A17,17,0,1,1,6,23,17,17,0,0,1,23,6Z"/></svg>
                </div>

                <div v-show="searchInputVisible" class="search-input-wrapper">
                    <input type="text" v-model="songName" class="search-input" placeholder="Add Song"/>
                    <button @click="searchSongs(songName)" class="search-button">Search</button>
                </div>
            </div>
        </div>
        <div>
            <div class="songs-section" v-show="!searchInputVisible">
                <div v-for="song in room_songs">
                    <div class="song-item"><span class="song-title">{{ song.title }}</span><span class="song-artist">- {{song.artist_title}}</span></div>
                </div>
            </div>


            <div v-for="song in songs" class="songs-section" v-show="searchInputVisible">
                <div v-for="item in song.items">
                    <div @click="addSong(rkey, item)" class="song-item"><span>+ </span><span class="song-title">{{item.name}}</span><span class="song-artist">- {{ item.album.artists[0].name }}</span></div>
                </div>
            </div>
        </div>

        <div class="player">
            <div class="song-info">
                <div class="album-cover"></div>
                <div class="song-details">
                    <h3>Song Title</h3>
                    <h4>Artist</h4>
                </div>
            </div>
            <div class="player-controls">
                <div class="player-button">
                    <button class="skip-button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 56 61.02">
                            <title>previous</title>
                            <path class="previous" d="M54.24,1a.76.76,0,0,1,.76.76V59.18a.75.75,0,0,1-1.14.65L8.5,33.64,7,32.78V59.69a.32.32,0,0,1-.32.33H1.32A.32.32,0,0,1,1,59.69V1.34A.32.32,0,0,1,1.32,1H6.68A.32.32,0,0,1,7,1.34V28.16l1.5-.87L53.86,1.1a.86.86,0,0,1,.38-.1h0m0-1a1.69,1.69,0,0,0-.88.24L8,26.43V1.34A1.32,1.32,0,0,0,6.68,0H1.32A1.32,1.32,0,0,0,0,1.34V59.69A1.33,1.33,0,0,0,1.32,61H6.68A1.33,1.33,0,0,0,8,59.69V34.51L53.36,60.7a1.79,1.79,0,0,0,.88.24A1.76,1.76,0,0,0,56,59.18V1.76A1.76,1.76,0,0,0,54.24,0Z"/>
                        </svg>
                    </button>
                    <button class="play-pause-button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 99 99">
                            <title>play-pause-button</title>
                            <g id="play-pause-button">
                                <path class="play-pause-circle" d="M49.5,1A48.5,48.5,0,1,1,1,49.5,48.56,48.56,0,0,1,49.5,1m0-1A49.5,49.5,0,1,0,99,49.5,49.5,49.5,0,0,0,49.5,0Z"/>
                                <path class="play" v-show="playSong" d="M31.64,19.8a.76.76,0,0,1,.38.11L81.74,48.62a.74.74,0,0,1,.38.65.75.75,0,0,1-.38.66L32,78.64a.75.75,0,0,1-.38.1.76.76,0,0,1-.76-.76V20.57a.77.77,0,0,1,.76-.77m0-1a1.76,1.76,0,0,0-1.76,1.77V78a1.76,1.76,0,0,0,1.76,1.76,1.69,1.69,0,0,0,.88-.24L82.24,50.8a1.76,1.76,0,0,0,0-3L32.52,19a1.79,1.79,0,0,0-.88-.24Z"/>
                                <g id="pause" v-show="!playSong">
                                    <path class="pause-rect" d="M39.71,19.5a1.79,1.79,0,0,1,1.79,1.79V77.71a1.79,1.79,0,0,1-1.79,1.79H37.29a1.79,1.79,0,0,1-1.79-1.79V21.29a1.79,1.79,0,0,1,1.79-1.79h2.42m0-1H37.29a2.79,2.79,0,0,0-2.79,2.79V77.71a2.79,2.79,0,0,0,2.79,2.79h2.42a2.79,2.79,0,0,0,2.79-2.79V21.29a2.79,2.79,0,0,0-2.79-2.79Z"/>
                                    <path class="pause-rect" d="M59.71,19.5a1.79,1.79,0,0,1,1.79,1.79V77.71a1.79,1.79,0,0,1-1.79,1.79H57.29a1.79,1.79,0,0,1-1.79-1.79V21.29a1.79,1.79,0,0,1,1.79-1.79h2.42m0-1H57.29a2.79,2.79,0,0,0-2.79,2.79V77.71a2.79,2.79,0,0,0,2.79,2.79h2.42a2.79,2.79,0,0,0,2.79-2.79V21.29a2.79,2.79,0,0,0-2.79-2.79Z"/>
                                </g>
                            </g>
                        </svg>
                    </button>
                    <button class="skip-button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 56 61.02">
                            <title>next</title>
                            <path class="next" d="M54.68,1a.32.32,0,0,1,.32.32V59.68a.32.32,0,0,1-.32.32H49.32a.32.32,0,0,1-.32-.32V32.86l-1.5.86L2.14,59.91a.76.76,0,0,1-.38.11A.77.77,0,0,1,1,59.25V1.84a.7.7,0,0,1,.23-.54.77.77,0,0,1,.53-.22.75.75,0,0,1,.38.1L47.5,27.37l1.5.87V1.32A.32.32,0,0,1,49.32,1h5.36m0-1H49.32A1.32,1.32,0,0,0,48,1.32V26.51L2.64.32A1.69,1.69,0,0,0,1.76.08,1.76,1.76,0,0,0,0,1.84V59.25A1.76,1.76,0,0,0,1.76,61a1.79,1.79,0,0,0,.88-.24L48,34.59V59.68A1.32,1.32,0,0,0,49.32,61h5.36A1.32,1.32,0,0,0,56,59.68V1.32A1.32,1.32,0,0,0,54.68,0Z"/>
                        </svg>
                    </button>
                </div>
                <div class="player-scrubber">
                    <div class="song-completion"></div>
                </div>
            </div>

        </div>
    </div>
    <!--<div class="room-wrapper bg-gradient text-white flex flex-col w-screen h-screen">-->
        <!--<div class="player-control-wrapper w-screen">-->
            <!--<div v-if="currentSong.title" class="song-info">-->
                <!--<div class="w-32 h-32 bg-blue-darker"></div>-->
                <!--<div class="flex flex-col">-->
                    <!--<h4>{{ currentSong.title }}</h4>-->
                    <!--<h5>{{currentSong.artist_title}}</h5>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="player-controls">-->

                <!--<button class="player-control-button">-->
                    <!--<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 76 111.37">-->
                        <!--<title>previous</title>-->
                        <!--<path class="previous" d="M70.58.82a8.28,8.28,0,0,0-3.47,1.66l-51,45.2c-.83.73-1.55,1.37-1.6,1.43a4.56,4.56,0,0,1-.21-1.81V2a2,2,0,0,0-2-2H2A2,2,0,0,0,0,2V109.37a2,2,0,0,0,2,2H12.28a2,2,0,0,0,2-2V63.74a8,8,0,0,1,.1-1.9c0,.06.88.79,1.71,1.52l51,45.21a8.26,8.26,0,0,0,3.47,1.65l4.72-3.73a13.09,13.09,0,0,0,.7-3.87V8.6a13.09,13.09,0,0,0-.7-3.87Z"/>-->
                    <!--</svg>-->
                <!--</button>-->
                <!--<div class="player-control-button">-->
                    <!--<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 115 115">-->
                        <!--<title>play-pause</title>-->
                        <!--<g id="play-pause">-->
                            <!--<circle class="play-pause-button" cx="53.84" cy="53.84" r="52.84"/>-->
                            <!--<path @click="play" v-show="playSong" id="play" class="play" d="M77.37,50a4.55,4.55,0,0,1,0,7.72L40.88,86.1c-2.73,2.08-5,.73-5-3.24V24.77c0-3.86,2.26-5.32,5-3.13Z"/>-->
                            <!--<g id="pause" @click="pause" v-show="!playSong">-->
                                <!--<rect class="pause-rect" x="35.89" y="21.31" width="8.39" height="65.43" rx="2" ry="2"/>-->
                                <!--<rect class="pause-rect" x="57.7" y="21.31" width="8.39" height="65.43" rx="2" ry="2"/>-->
                            <!--</g>-->
                        <!--</g>-->
                    <!--</svg>-->
                    <!--&lt;!&ndash;<button @click="play" v-show="playSong">Play</button>&ndash;&gt;-->
                    <!--&lt;!&ndash;<button @click="pause" v-show="!playSong">Pause</button>&ndash;&gt;-->
                <!--</div>-->
                <!--<button @click="next" class="player-control-button">-->
                    <!--<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 77.14 111.43">-->
                        <!--<title>next</title>-->
                        <!--<path class="next" d="M64.86,0a2,2,0,0,0-2,2V48.59c0,1.1-.2,1.77-.44,1.49S60.77,48.53,60,47.8L8.89,2.56A8.15,8.15,0,0,0,5.42.91L.7,4.64A13.15,13.15,0,0,0,0,8.52v94.06a13.15,13.15,0,0,0,.7,3.88l4.72,3.91a8.17,8.17,0,0,0,3.47-1.66L60,63.48l2-1.8c.28-.26.91-.1.91,1v46.75a2,2,0,0,0,2,2H75.14a2,2,0,0,0,2-2V2a2,2,0,0,0-2-2Z"/>-->
                    <!--</svg>-->
                <!--</button>-->
            <!--</div>-->

        <!--</div>-->
        <!--<div class="main-section flex">-->
            <!--<div class="flex w-1/3 sidebar">-->
                <!--<h3>{{name}} - <span>{{code}}</span></h3>-->
                <!--<h3>Add Songs</h3>-->
                <!--<h3>Queue</h3>-->
            <!--</div>-->
            <!--<div class="flex w-2/3 content-wrapper">-->
                <!--<div>-->
                    <!--<span>Add Song</span>-->
                    <!--<input type="text" v-model="songName"/>-->
                    <!--<button @click="searchSongs(songName)">Search</button>-->
                <!--</div>-->
                <!--<div>-->
                    <!--<div v-if="queue.length">-->
                        <!--<span>Queue:</span>-->
                        <!--<div v-for="song in queue">-->
                            <!--{{ song.title }} - {{song.artist_title}}-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->
                <!--<div>-->
                    <!--All Songs:-->
                    <!--<div v-for="song in room_songs">-->
                        <!--{{ song.title }} - {{song.artist_title}}-->
                    <!--</div>-->

                    <!--<div v-for="song in songs">-->
                        <!--<div v-for="item in song.items">-->
                            <!--<span @click="addSong(rkey, item)">{{item.name}} - {{ item.album.artists[0].name }}</span>-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->


        <!--<spotify-web-player v-if="!has_parent || !existing_player_id"-->
            <!--:accessToken="access_token"-->
            <!--:roomName="name"-->
            <!--:roomKey="rkey"-->
            <!--@deviceId="storePlayerId"-->
            <!--@next="next"-->
            <!--&gt;</spotify-web-player>-->

    <!--</div>-->
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
                playerId: '',
                currentSong: {},
                queue: [],
                room_songs: JSON.parse(this.raw_room_songs),
                playSong: true,
                searchInputVisible: false
            }
        },

        props : [
            'name',
            'rkey',
            'code',
            'access_token',
            'existing_player_id',
            'has_parent',
            'raw_room_songs'
        ],

        created() {
            if(this.existing_player_id) {
                this.storePlayerId(this.existing_player_id);
            }

            Echo.channel(`song-queue`)
                .listen('SongQueueStarted', (e) => {
                    this.currentSong = e.song;
                    console.log(this.currentSong);
                });

            Echo.channel(`song-queue`)
                .listen('SongQueueUpdated', (e) => {
                    this.queue = e.queue;
                });

            Echo.channel(`songs`)
                .listen('SongAdded', (e) => {
                    this.room_songs.push(e.song);
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
                   this.songs = [];
               });
           },

            play() {
                if (this.currentSong.title) {
                    axios.put(`/room/${this.rkey}/resume`, {'device_id' : this.playerId});
                    this.playSong = !this.playSong;
                    return;
                }
                axios.put(`/room/${this.rkey}/device/${this.playerId}/play`);
                this.playSong = !this.playSong;
            },

            pause() {
                axios.put(`/room/${this.rkey}/pause`, {'device_id' : this.playerId});
                this.playSong = !this.playSong;
            },

            // resume() {
            //     axios.put(`/room/${this.rkey}/resume`, {'device_id' : this.playerId});
            // },

            next() {
                axios.put(`/room/${this.rkey}/next`, {'device_id' : this.playerId});
            },

            storePlayerId(deviceId) {
               this.playerId = deviceId;
            }
        }
    }
</script>
