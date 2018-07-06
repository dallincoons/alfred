<template>
    <div class="main-wrapper bg-gradient h-screen">
        <div class="room-heading">
            <h2 class="room-code" :class="{roomCodeSearch : searchInputVisible}">{{code}}</h2>
            <div class="search-section">
                <div class="search-icon" @click="toggleSearchInput()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 56.97" v-show="!searchInputVisible"><title>search</title><path id="search" data-name="search" d="M54.16,51.89,40.6,37.79a23,23,0,1,0-4.42,4.05L49.84,56.05a3,3,0,0,0,4.32-4.16ZM23,6A17,17,0,1,1,6,23,17,17,0,0,1,23,6Z"/></svg>
                </div>

                <div v-show="searchInputVisible" class="search-input-wrapper">
                    <input type="search" v-model="songName" class="search-input" placeholder="Add Song" @keyup.enter="searchSongs(songName)" autofocus/>
                    <div @click="closeSearch()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 59.72 76.62" v-show="searchInputVisible" class="search-close" >
                            <title>close</title><path id="close" class="search-close" d="M59.37,71.9,34,35.66l21.68-31A1.94,1.94,0,0,0,55.17,2L52.81.35a1.94,1.94,0,0,0-2.7.48l-20.26,29L9.57.84A1.94,1.94,0,0,0,6.87.36L4.51,2A1.93,1.93,0,0,0,4,4.72l21.69,31L.35,71.92a1.94,1.94,0,0,0,.48,2.7l2.36,1.65a1.94,1.94,0,0,0,2.7-.48l24-34.24,24,34.23a1.93,1.93,0,0,0,2.7.47l2.35-1.65A1.94,1.94,0,0,0,59.37,71.9Z"/>
                        </svg>
                    </div>
                    <button @click="searchSongs(songName)" class="search-button">Search</button>
                </div>
            </div>
        </div>
        <div class="room-content">
            <div class="songs-section" v-show="!songSearched">
                <div v-for="song in room_songs">
                    <div class="song-item">
                        <span class="song-title" @click="playSelectedSong(song.id)">{{ song.title }}</span>
                        <span class="song-artist">{{song.artist_title}}</span>
                        <span class="song-delete" @click="deleteSong(song)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 85.93 97.77">
                                <title>trash-can</title>
                                <g id="trash-can">
                                    <path id="trash-outline" d="M30.37,0H55.58a2.13,2.13,0,0,0,.35.14A8.57,8.57,0,0,1,63,8.9v2.55h1.13c4.42,0,8.84,0,13.27,0a8.57,8.57,0,0,1,3.3,16.46A1,1,0,0,0,80,29c-.17,2.51-.4,5-.61,7.51q-.65,7.52-1.27,15l-1,12.83c-.35,4.25-.71,8.49-1,12.74s-.67,8.24-1,12.35a9,9,0,0,1-2.63,5.87,10.43,10.43,0,0,1-4.74,2.48H18.34a3.54,3.54,0,0,0-.34-.16c-4.08-1-6.46-3.53-6.93-7.72-.55-4.89-.88-9.81-1.29-14.73C9.18,67.88,8.61,60.58,8,53.3c-.67-8.08-1.38-16.16-2-24.24a1.18,1.18,0,0,0-.84-1.18A8,8,0,0,1,.8,23.66,8.59,8.59,0,0,1,8.64,11.47c4.14,0,8.28,0,12.41,0h1.88c0-1,0-1.93,0-2.84A8.59,8.59,0,0,1,25.06,3,9.57,9.57,0,0,1,30.37,0ZM74.2,28.72H11.75c.19,2.33.36,4.58.54,6.83q.63,7.61,1.27,15.2l1,12.45q.54,6.47,1.09,12.93c.35,4.24.67,8.49,1.07,12.73C17,91.24,18,92,20.37,92.05H65.9c1.83,0,3.09-1,3.25-2.55.28-3,.51-5.95.76-8.93.35-4.15.68-8.3,1-12.45q.61-7.56,1.25-15.11c.36-4.28.73-8.55,1.08-12.83C73.59,36.37,73.89,32.57,74.2,28.72Zm-31.27-5.8H66.32c3.6,0,7.19,0,10.79,0A2.85,2.85,0,0,0,80,19c-.62-1.41-1.83-1.79-3.28-1.79q-27.8,0-55.57,0c-4.07,0-8.15,0-12.22,0A2.89,2.89,0,0,0,6,21.1a3.06,3.06,0,0,0,3.17,1.82ZM57.3,11.44V9.08c0-2.21-1.16-3.35-3.35-3.35-3.89,0-7.77,0-11.65,0-3.47,0-6.93,0-10.4,0-1.56,0-2.77.69-3,2a28.77,28.77,0,0,0-.28,3.75Z"/>
                                    <path id="line_3" data-name="line 3" d="M21.76,38.17c-.06-2.43,1-3.77,2.95-3.75a2.87,2.87,0,0,1,2.88,2.64q.51,7.74,1,15.48.55,8.76,1.1,17.53.43,7.2.85,14.41a2.74,2.74,0,0,1-1.47,2.67,2.78,2.78,0,0,1-3,0,2.73,2.73,0,0,1-1.41-2.22q-.41-6.08-.77-12.17L22.8,55q-.42-7-.85-13.93C21.89,40.07,21.82,39.06,21.76,38.17Z"/>
                                    <path id="line_2" data-name="line 2" d="M62.75,38.06c-.36,5.81-.74,11.62-1.1,17.44-.22,3.53-.43,7.07-.65,10.61q-.45,7.31-.92,14.61c-.08,1.3-.15,2.6-.24,3.9a2.91,2.91,0,0,1-3.1,2.92A2.87,2.87,0,0,1,54,84.39c.18-3.77.42-7.54.66-11.31.29-4.74.6-9.47.9-14.21q.56-8.82,1.1-17.63c.08-1.27.17-2.53.25-3.8a2.9,2.9,0,0,1,3.09-3,2.93,2.93,0,0,1,2.77,3.25C62.75,37.8,62.76,37.93,62.75,38.06Z"/>
                                    <path id="line_1" data-name="line 1" d="M45.18,61.08V84.5a2.85,2.85,0,0,1-1.5,2.66,2.78,2.78,0,0,1-3,0,2.92,2.92,0,0,1-1.4-2.72c0-5.79,0-11.58,0-17.37q0-14.74,0-29.47a2.94,2.94,0,1,1,5.86,0C45.17,45.4,45.18,53.24,45.18,61.08Z"/>
                                </g>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
            <div v-for="item in songs"  v-show="songSearched">
                    <div @click="addSong(rkey, item)" class="song-item search-song">
                        <div class="search-song-added">
                            <span v-if="songIsNotAdded(item)" class="search-plus">+</span>
                            <div v-else class="search-check-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 611.98 418.93" class="search-check">
                                <title>check</title>
                                <path d="M217.63,418.93h-.06a24.65,24.65,0,0,1-17.38-7.25L7.15,217.24A24.57,24.57,0,0,1,42,182.59l175.66,177L570,7.2A24.58,24.58,0,0,1,604.78,42L235,411.74A24.59,24.59,0,0,1,217.63,418.93Z"/></svg>
                            </div>
                        </div>
                        <span class="song-title">{{item.name}}</span>
                        <span class="song-artist">{{ item.album.artists[0].name }}</span>
                    </div>
            </div>
        </div>

        <div class="player">
            <div class="song-info">
                <div class="album-cover"><img :src="currentSong.big_image"></div>
                <div class="song-details">
                    <h3 class="current-song-title">{{ currentSong.title }}</h3>
                    <h5 class="current-song-artist">{{currentSong.artist_title}}</h5>
                    <h6 v-show="currentSong.added_by" class="current-song-added-by" :class="{show : showSongDetails}">Added by {{currentSong.added_by}}</h6>
                </div>
            </div>
            <div class="player-controls">
                <div class="player-button disabled" :class="{ notDisabled : playNotDisabled}">
                    <button class="skip-button previous" @click="previous()">
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
                            @player_state_changed="playerStateChanged"
                            v-on:player-ready="enablePlay()"></spotify-web-player>
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
                added: false,
                songSearched: false,
                playNotDisabled: false,
                showSongDetails: false
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

            Echo.channel(`song-queue`)
                .listen('SongQueueStarted', (e) => {
                    this.currentSong = e.song;
                });
        },

        methods : {
        enablePlay() {
            this.playNotDisabled = true;
        },

          closeSearch(){
            this.toggleSearchInput();
            this.songSearched = false;
            this.songName = '';
          },

           searchSongs(song) {
               axios.get('/spotify/songs?q=' + song + '&room=' + this.rkey).then((response) => {
                   this.songs = response.data.map((song) => {
                        song.checked = false;
                        return song;
                   });
               });
               this.songSearched = true;
           },

           addSong(room, addedSong) {
               if (!this.songIsNotAdded(addedSong)) {
                    let foundSong = this.room_songs.find(song => {
                        return song.external_id === addedSong.id;
                    });
                   axios.delete(`/room/${this.rkey}/` + foundSong.id).then(response => {
                       this.songs = this.songs.map(song => {
                           if (song.id === addedSong.id) {
                               song.checked = false;
                           }
                           return song;
                       });
                   });
                   return;
               }

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
                    axios.put(`/room/${this.rkey}/resume`, {'device_id' : this.playerId}).then((response) => {
                        this.playSong = !this.playSong;
                    });
                    return;
                }
                axios.put(`/room/${this.rkey}/play`).then((response) => {
                    this.playSong = !this.playSong;
                    this.showSongDetails = true;
                });
            },

            pause() {
                axios.put(`/room/${this.rkey}/pause`, {'device_id' : this.playerId}).then((response) => {
                    this.playSong = !this.playSong;
                });
            },

            next() {
                axios.put(`/room/${this.rkey}/next`);
            },

            previous() {
                axios.put(`/room/${this.rkey}/previous`);
            },

            storePlayerId(deviceId) {
               this.playerId = deviceId;
            },

            deleteSong(song) {
                // let confirmDelete = confirm("Are you sure you want to remove " + song.title + " from the playlist?");
                // if (confirmDelete === true) {
                    axios.delete(`/room/${this.rkey}/` + song.id).then(response => {
                        this.room_songs = response.data;
                    })
                // }
            },

            songIsNotAdded(item) {
                return !item.checked && !item.isAdded;
            },

            toggleSearchInput() {
                this.searchInputVisible = !this.searchInputVisible;
            },

            playSelectedSong(songId) {
                axios.patch(`/room/${this.rkey}/song/${songId}/play`, {'device_id' : this.playerId}).then(response => {
                    this.playSong = false;
                });
            },

            playerStateChanged(song) {
                if (song.id !== this.currentSong.id) {
                    this.currentSong = song;
                }
            },
        }
    }
</script>
