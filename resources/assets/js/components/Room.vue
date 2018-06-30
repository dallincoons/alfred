<template>
    <div class="main-wrapper bg-gradient h-screen">
        <div class="room-heading">
            <h2 class="room-code">{{code}}</h2>
            <div class="search-section">
                <div class="search-icon" @click="searchInputVisible = !searchInputVisible">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 56.97" v-show="!searchInputVisible"><title>search</title><path id="search" data-name="search" d="M54.16,51.89,40.6,37.79a23,23,0,1,0-4.42,4.05L49.84,56.05a3,3,0,0,0,4.32-4.16ZM23,6A17,17,0,1,1,6,23,17,17,0,0,1,23,6Z"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 59.72 76.62" v-show="searchInputVisible" class="search-close"><title>close</title><path id="close" class="search-close" d="M59.37,71.9,34,35.66l21.68-31A1.94,1.94,0,0,0,55.17,2L52.81.35a1.94,1.94,0,0,0-2.7.48l-20.26,29L9.57.84A1.94,1.94,0,0,0,6.87.36L4.51,2A1.93,1.93,0,0,0,4,4.72l21.69,31L.35,71.92a1.94,1.94,0,0,0,.48,2.7l2.36,1.65a1.94,1.94,0,0,0,2.7-.48l24-34.24,24,34.23a1.93,1.93,0,0,0,2.7.47l2.35-1.65A1.94,1.94,0,0,0,59.37,71.9Z"/>
                    </svg>
                </div>

                <div v-show="searchInputVisible" class="search-input-wrapper">
                    <input type="text" v-model="songName" class="search-input" placeholder="Add Song" @keyup.enter="searchSongs(songName)"/>
                    <button @click="searchSongs(songName)" class="search-button">Search</button>
                </div>
            </div>
        </div>
        <div class="room-content">
            <div class="songs-section" v-show="!searchInputVisible">
                <div v-for="song in room_songs">
                    <div class="song-item"><span class="song-title">{{ song.title }}</span><span class="song-artist">- {{song.artist_title}}</span><span class="ml-2" @click="deleteSong(song.id)">x</span></div>
                </div>
            </div>

            <div v-for="item in songs" class="songs-section" v-show="searchInputVisible">
                    <div @click="addSong(rkey, item)" class="song-item">
                        <span v-if="!item.checked">+</span>
                        <div v-else class="search-check-wrapper"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 611.98 418.93" class="search-check"><title>check</title><path d="M217.63,418.93h-.06a24.65,24.65,0,0,1-17.38-7.25L7.15,217.24A24.57,24.57,0,0,1,42,182.59l175.66,177L570,7.2A24.58,24.58,0,0,1,604.78,42L235,411.74A24.59,24.59,0,0,1,217.63,418.93Z"/></svg></div>
                        <span class="song-title">{{item.name}}</span>
                        <span class="song-artist">- {{ item.album.artists[0].name }}</span>
                    </div>
            </div>
        </div>

        <div class="player">
            <div class="player-scrubber">
                <div class="song-completion"></div>
            </div>
            <div class="song-info">
                <div class="album-cover">{{currentSong.big_image}}</div>
                <div class="song-details">
                    <h3>{{ currentSong.title }}</h3>
                    <h5>{{currentSong.artist_title}}</h5>
                </div>
            </div>
            <div class="player-controls">
                <div class="player-button">
                    <button class="skip-button previous">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 51">
                            <title>skip-previous</title>
                            <path class="cls-1" d="M39.46,50.5a2,2,0,0,1-2-2V29.25L5.25,47.87a3.11,3.11,0,0,1-1.58.43A3.18,3.18,0,0,1,.5,45.13V5.87A3.17,3.17,0,0,1,5.25,3.12L37.5,21.75V2.46a2,2,0,0,1,2-2h3.08a2,2,0,0,1,2,2V48.54a2,2,0,0,1-2,2Z"/>
                        </svg>
                    </button>
                    <button class="play-pause-button">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                            <title>play-pause-button</title>
                            <g id="play-pause-button">
                                <g class="play-pause-circle">
                                    <circle class="cls-1" cx="50" cy="50" r="49.5"/>
                                </g>
                                <g id="play" v-show="playSong" class="play-pause-action" @click="play">
                                    <path class="cls-1" d="M39.67,73a3.18,3.18,0,0,1-3.17-3.17V30.52a3.18,3.18,0,0,1,3.17-3.17,3.11,3.11,0,0,1,1.58.43l34,19.64a3.15,3.15,0,0,1,0,5.47l-34,19.64a3.21,3.21,0,0,1-1.58.43Z"/>
                                </g>
                                <g id="pause" v-show="!playSong" class="play-pause-action" @click="pause">
                                        <rect class="cls-1" x="33.5" y="27" width="11" height="46" rx="1.27" ry="1.27"/>
                                        <rect class="cls-1" x="56.5" y="27" width="11" height="46" rx="1.27" ry="1.27"/>
                                </g>
                            </g>
                        </svg>
                    </button>
                    <button class="skip-button" @click="next">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 51">
                            <title>skip-next</title>
                            <path class="cls-1" d="M39.46,50.5a2,2,0,0,1-2-2V29.25L5.25,47.87a3.11,3.11,0,0,1-1.58.43A3.18,3.18,0,0,1,.5,45.13V5.87A3.17,3.17,0,0,1,5.25,3.12L37.5,21.75V2.46a2,2,0,0,1,2-2h3.08a2,2,0,0,1,2,2V48.54a2,2,0,0,1-2,2Z"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
        <spotify-web-player v-if="!has_parent || !existing_player_id"
                            :accessToken="access_token"
                            :roomName="name"
                            :roomKey="rkey"
                            @deviceId="storePlayerId"
                            @next="next"
        ></spotify-web-player>
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
                playerId: '',
                currentSong: {},
                queue: [],
                room_songs: JSON.parse(this.raw_room_songs),
                playSong: true,
                searchInputVisible: false,
                added: false
            }
        },

        props : [
            'name',
            'rkey',
            'code',
            'access_token',
            'existing_player_id',
            'has_parent',
            'raw_room_songs',
        ],

        created() {
            if(this.existing_player_id) {
                this.storePlayerId(this.existing_player_id);
            }

            Echo.channel(`songs`)
                .listen('SongAdded', (e) => {
                    this.room_songs.push(e.song);
                });
        },

        methods : {
           searchSongs(song) {
               axios.get('/spotify/songs?q=' + song).then((response) => {
                   this.songs = response.data.tracks.items.map((song) => {
                        song.checked = false;
                        return song;
                   });
               });
           },

           addSong(room, addedSong) {
               axios.post('/room/' + room + '/song', {song: addedSong}).then((response) => {
                   this.songs = this.songs.map(song => {
                        if (song.id === addedSong.id) {
                            song.checked = true;
                        }
                        return song;
                   });
               });
               this.added = true;
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
            },

            deleteSong(songId) {
                axios.delete(`/room/${this.rkey}/` + songId);
            }
        }
    }
</script>
