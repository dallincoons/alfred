<template>
    <div class="main-wrapper h-screen room-wrapper" :class="{searchActive : songSearched}">
        <div class="room-header">
            <h1 class="room-code">{{code}}</h1>
            <div class="logo-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 815.45 102.26">
                    <path class="cls-party" d="M421.47,63.3H405V97.08h-18.9v-93h35.33c22.32,0,32.24,13.64,32.24,29.6C453.71,49.81,443.79,63.3,421.47,63.3Zm-3.1-45.72H405V49.81h13.33c12.25,0,16.59-7.28,16.59-16.12C435,25,430.62,17.58,418.37,17.58Z"/>
                    <path class="cls-party" d="M524.07,97.08l-9.45-22.93H477L467.5,97.08H449.22l39.52-93h14.72l39.52,93Zm-28.2-69.89L482.07,61H509.5Z"/>
                    <path class="cls-party" d="M621.09,98.17c-11.15,0-20.14-4.81-26.81-18.29L584.36,60H573.51v37h-18.9v-93h36.57c22,0,31.46,13,31.46,28.06,0,13.17-7.43,23.4-20.45,26.34l9.14,16c4.65,8.05,9,10.84,15.5,10.84a15.52,15.52,0,0,0,5.27-.77V96.46A39.49,39.49,0,0,1,621.09,98.17ZM573.51,46.71h14.57c11.78,0,16-6.2,16-14.41,0-8.37-4.19-14.72-16-14.72H573.51Z"/>
                    <path class="cls-party" d="M679.52,18.82V97.08h-18.9V18.82H629.15V4.09H711V18.82Z"/>
                    <path class="cls-party" d="M775.33,59.89V97.08H756.42V59.89L720,4.09h20.62l25.57,40.45L791.76,4.09h20Z"/>
                    <path class="cls-pool" d="M37.34,63.84H20.91V97.62H2v-93H37.34c22.31,0,32.23,13.64,32.23,29.61S59.65,63.84,37.34,63.84Zm-3.1-45.72H20.91V50.36H34.24c12.24,0,16.58-7.29,16.58-16.12S46.48,18.12,34.24,18.12Z"/>
                    <path class="cls-pool" d="M130.48,100.26C102,100.26,79.8,79.65,79.8,51.13S102,2,130.48,2,180.7,22.61,180.7,51.13,158.85,100.26,130.48,100.26Zm0-83.23c-18.59,0-30.84,14-30.84,34.1s12.25,34.1,30.84,34.1c18.29,0,30.38-14,30.38-34.1S148.77,17,130.48,17Z"/>
                    <path class="cls-pool" d="M243,100.26c-28.51,0-50.67-20.61-50.67-49.13S214.49,2,243,2s50.22,20.61,50.22,49.13S271.37,100.26,243,100.26ZM243,17c-18.59,0-30.84,14-30.84,34.1s12.25,34.1,30.84,34.1c18.29,0,30.38-14,30.38-34.1S261.29,17,243,17Z"/>
                    <path class="cls-pool" d="M309.34,97.62v-93h18.91V82.9h41.69V97.62Z"/>
                </svg>
            </div>
            <div class="song-actions-wrapper" :class="{justifyItems : currentSongVisible}">
                <div class="song-details" v-show="currentSongVisible" :class="{songVisible : currentSongVisible}">
                    <h3 class="current-song-title">{{ currentSong.name }}</h3>
                    <h5 class="current-song-artist">{{currentSong.artist}} <span class="current-song-added-by"> | {{currentSong.added_by}}</span></h5>
                </div>
                <div class="room-actions">
                    <div class="song-icon"  @click="showCurrentSong()" :class="{animateSongIcon : currentSongVisible}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 76 70.9">
                            <rect class="cls-song song-line-1" y="16.87" width="7.94" height="36.83" rx="3"/>
                            <rect class="cls-song song-line-2" x="10.91" y="10.59" width="7.94" height="49.28" rx="3"/>
                            <rect class="cls-song song-line-3" x="22.51" width="7.94" height="70.9" rx="3"/>
                            <rect class="cls-song song-line-4" x="33.85" y="15.44" width="7.94" height="40.02" rx="3"/>
                            <rect class="cls-song song-line-5" x="45.59" y="20.73" width="7.94" height="29.44" rx="3"/>
                            <rect class="cls-song song-line-6" x="57.03" y="4.41" width="7.94" height="62.07" rx="3"/>
                            <rect class="cls-song song-line-7" x="68.06" y="10.59" width="7.94" height="49.28" rx="3"/>
                        </svg>
                    </div>
                    <div class="playlist-icon" @click="showPlaylist()" :class="{playlistClose : playlistVisible}">
                        <span class="hamburger-line line-1"></span>
                        <span class="hamburger-line line-2"></span>
                        <span class="hamburger-line line-3"></span>
                        <span class="hamburger-line line-4"></span>
                    </div>
                </div>
            </div>

        </div>
        <div class="room-content" :class="{expandHeight : playlistVisible}">

            <div class="waves-wrapper" v-show="!playlistVisible">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1197.27 1593.76">
                    <path class="cls-wave" d="M1134.43,382.39c-17.71-3.56-21.72-30.74-32.82-42.87-13.89-15.18-34.26-17.86-53.34-11.9-45.08,14.1-81.8,99.34-134.71,57.62-17.25-13.59-28.1-30.21-50-37.22a92,92,0,0,0-55.52.43c-17,5.49-32.35,15.95-42.77,30.54-15.35,21.49-26.41,63.74-62.33,44.81-20.78-11-18.25-39.74-31.52-57.05S635.59,342.94,614.56,344c-22.06,1.11-43.56,9.48-61.16,22.68a111.58,111.58,0,0,0-21.16,20.71c-8.86,11.35-14.7,25.29-27.54,32.94-23.15,13.79-54.72,10.57-77.86-1.27a89.77,89.77,0,0,1-28.67-22.78c-8.28-10.14-12.1-24-23.13-31.71-13.45-9.42-33.35-5.08-48.35-1.77-20.34,4.48-38.21,14-46.58,33.95-6.52,15.55-10.21,32.48-30.77,33.35-18.26.78-27.19-15.33-36-28.53C202.28,385,190.16,371.77,171,364.79c-38.21-13.88-84.26.92-104.31,36.75"/>
                    <path class="cls-wave" d="M84.14,530.1c22.69-40.55,82.47-42.07,108.55-4.77,10.5,15,19.17,32.31,37.05,39.77,15.55,6.48,34.07,3.84,47.62-6,18.57-13.54,15.87-41.25,35.84-52.13,9.12-5,20.06-6.8,30.21-8.24,7-1,15.5-3,21.69,2,5.76,4.63,9.29,15.7,13.7,21.81,23.05,31.95,64.11,49.43,103.1,44.33,18.46-2.42,36.42-9.62,48.83-23.89,8-9.22,13.18-20.45,21.39-29.56a95.13,95.13,0,0,1,41.56-26.74c27-8.61,57.94-4.79,68.62,25,6.91,19.25,11.1,37.62,30.43,48a56.77,56.77,0,0,0,46.67,2.86c23.34-9.17,28-31.61,40.5-50.69,12.29-18.71,33.78-29,56-29.27,29.53-.36,42.66,17.83,63.38,35.38,17.86,15.12,42.1,21,64.68,13.94,23.33-7.26,39.15-26.5,55.77-43.13,20.43-20.45,55-40.26,73.55-7.18,9.12,16.21,15.51,34.64,35.81,38.72"/>
                    <path class="cls-wave" d="M73.2,759.6c-.31-24.76,29.82-20,43.46-11.44,10.48,6.6,18.76,16.1,27.85,24.36C161.3,787.79,180,800.18,203.72,798c17.27-1.58,31-11,43-22.89,13.35-13.28,28-38.68,50.29-33.83,17.88,3.89,30.79,24.75,44.07,35.87,13.71,11.48,29.21,18.15,47.44,15.83,47.74-6.09,69.86-65.15,119.59-62.63,26.29,1.33,44.61,16.78,57,39,9,16.19,19,29.89,37.95,34.49,45.8,11.14,76.46-29.55,105-57.29,18.14-17.62,44.37-35.06,69.76-21.31C795,734.54,806.23,753,819.31,767c25.3,27,57.44,34.8,89.6,12.93,23.22-15.79,40.26-43.54,67.79-52.54,31.66-10.35,44,21.17,61.92,39.59,30.13,30.94,80.43,29.86,110.8,0"/>
                    <path class="cls-wave" d="M1135.11,654c-17.48,17.17-44.71,22.48-67,11.38-21.26-10.57-29.56-35.08-48.3-48.7-38.33-27.88-76,6.54-103.48,32.32-18.48,17.33-42.83,34.87-67.53,18.65-16.42-10.78-27-28.85-40.67-42.59-29.34-29.48-66.55-29.56-99.46-4.49-34.14,26-88.65,107.14-126.06,39.87-18.85-33.9-53.61-55.21-93.42-47.57-19.89,3.81-35.75,15.86-50.72,28.8-20.89,18-48.08,44.91-77.25,26.75-28.05-17.45-47.12-59.1-86.7-43.26-18.75,7.51-29.79,25.35-43.81,38.79-32.24,30.89-59.75,5.61-86.18-18-16.24-14.52-36.88-28.69-59.84-23.34C66.24,626.91,52.72,641.92,53,661"/>
                    <path class="cls-wave" d="M1175.6,930.1c-18.2,9.58-32.65-15.77-42.08-26.83a134,134,0,0,0-26.43-23.66c-18.79-12.81-41.89-21.12-64.84-18.38-24.26,2.9-41,18.55-55.38,37.07-15.08,19.43-39.39,54.37-67.47,34.3-29.17-20.85-33.53-64.8-69.3-79.36-53.08-21.62-86.22,41.66-123.63,65.1-15,9.38-33.29,13.43-48,1.21-8.77-7.3-14.15-18.31-19.83-28C648.27,873.88,637,857,617.21,849c-49-19.62-87.11,17.83-120.26,47.65-21.16,19-45.42,28.93-67.87,6.05-13.95-14.2-23.31-32.36-37.93-46-36.36-33.84-78.41-12.69-109.53,17-20.51,19.55-53.15,59-85.54,42.82-23.44-11.71-26.16-45.6-47.74-60.78-19.84-13.94-47.69-11.79-66.8,2.36-21.62,16-29.66,43.2-27.57,69.14"/>
                    <path class="cls-wave" d="M74.21,1041c-2.43-30.23,17.53-72.12,54.89-58.61,23.6,8.53,27.93,40.27,44.8,56.79,35.43,34.7,83.8,1.11,111.09-26.49,18.88-19.09,46.92-49.47,76.95-37.65,18.44,7.26,29.51,27,41.12,41.87,25.24,32.43,59.45,45.47,95.14,18.67,19.26-14.46,34-35.19,55.11-47,25.2-14.12,57.31-15.53,76.32,8.89,13,16.68,19.83,37.79,36.4,51.67,15.48,13,35.67,15.23,54.43,8.5,23-8.26,39.91-26.62,56.7-43.52,19.84-20,50.37-44.29,76.88-20.81,24.87,22,31.78,62.82,65,75.92,62.28,24.54,76.23-71.41,128.67-74.5,33.5-2,60,22.48,79.43,46.62,14.87,18.47,35.27,32.32,58.64,20"/>
                    <path class="cls-wave" d="M1177.34,1145.54c-34.5,38.92-87.31,86.17-135.56,38-23.81-23.75-39.81-58.43-72.4-71.92-38.07-15.75-72.54,9.92-94.41,39.48-13.32,18-26.78,57.43-55,50.82-26.63-6.25-38.56-38.62-57.38-55.55-30.21-27.19-75.29-25.06-104.75,1.87-19.1,17.45-32.38,39.39-56.78,50.53-29.24,13.36-64.94,7.77-82.31-21-10.23-16.91-14.58-35.89-33-46.33s-41.45-8.54-60.42-1.16c-38.85,15.13-96.88,94-139.88,47.37-14.18-15.38-21-36.7-38.91-48.77s-39.33-10.19-57.77-.07c-23.26,12.76-37.68,35.48-58.25,51.4-13.87,10.74-34.83,22.79-51,9.09-11.53-9.74-13-28-14.19-41.89"/>
                    <path class="cls-wave" d="M45.07,1260.67c3.36,40.23,25.44,82,72.64,63.74,40.44-15.68,89.73-108.56,131.45-50.44,10.75,15,19,32.54,35.29,42.5,19.42,11.85,43.28,9.67,63.55,1.32,41.56-17.13,110.12-104.21,145.5-32.71,10.13,20.47,20,37,41.68,46.88s45.93,9.4,67.79,1.09c23-8.75,39.43-24.72,55.48-42.68,21.5-24.07,51.08-41.64,82.49-22,33.09,20.66,58.4,92.84,109.41,61,36.52-22.78,51.53-107.75,109.64-86.6,41.61,15.16,54.68,70.07,95.51,88.65,54.38,24.75,102.94-20.91,136.16-58.38"/>
                    <path class="cls-wave" d="M1135,62.2c-2.44-17.54-16.59-29.8-33.62-33.23-22.48-4.53-42.82,8.93-58.72,23.24-21.54,19.4-41.35,45.32-74.12,36.4-30.24-8.23-39-42.36-60.36-61.74C879.82,1.14,847.32,9.22,826.28,38.16c-10.67,14.67-18.08,32.23-31,45.19-19,19.09-68.48,20.49-85.88-2.34-12.82-16.81-16-37.05-32.78-51.5C662.06,17,643.39,9.59,624.3,7.88,580.57,3.94,553.52,31.47,530,64.58c-15.51,21.84-32.34,44.24-61.45,45.85-31.64,1.75-48.91-19.09-65.22-42.69-10.15-14.69-21.23-29.95-38.56-36.52-20.19-7.64-40.87-1.13-57.93,10.77-25,17.42-41.85,49-71.48,59.39-32.58,11.4-49.64-27.59-77.23-37C103.79,45.81,59.06,91.68,44.77,139.75"/>
                    <path class="cls-wave" d="M64.28,291C72.1,264.69,88,238.7,115.41,229.72c33.22-10.88,49.85,9.85,73.36,27.71,39.79,30.22,78.12-3.13,106.22-32.11,18.39-19,46.13-44.08,72.59-23.51,15.23,11.84,23.21,31.48,35.61,45.95,24.94,29.12,68.59,37.92,102.09,18,37.57-22.35,48.28-77.34,92.28-89.77,19.6-5.54,41.93-1.75,58.88,9.4,19.55,12.86,23.2,33.45,35.49,51.67,20.31,30.09,76.85,34.92,106,16.12,12.43-8,21.24-20.58,29.07-32.83,8.82-13.8,17.44-34.09,33.14-41.31,29.37-13.51,48.77,28,62.75,46.07,13.58,17.59,31.8,29.69,54.35,31.18,24.41,1.61,43.91-10,61.26-26,10.35-9.55,19.52-20.77,31.58-28.32,15.58-9.75,42-12.59,45.37,11.45"/>
                    <path class="cls-wave" d="M5.78,1522.09c27.47,33.16,75.41,42.85,113.4,22.28,18.43-10,33.06-26.26,52.08-35.19,29.1-13.67,47.45,7.31,65.55,27.13,27.86,30.53,68.25,48.23,103.9,17.37,17.75-15.37,28-39.07,50-49.43,28.64-13.5,58.77-3.67,79,18.93,14.17,15.84,25.77,33.86,45.64,43.14a76.89,76.89,0,0,0,62.23.83c22.2-9.65,34.54-29.63,50.3-46.81,17-18.57,38.92-27.39,63.95-22.37,24.31,4.87,39.49,20.06,49.57,41.84,9.15,19.75,19.8,37,43.2,40,16.64,2.1,33.59-3.77,47.2-13.18,21.84-15.11,31.08-51.28,61.1-50.71,33.14.64,55.62,30.15,79.87,48.48,18.12,13.71,38.38,22.53,61.46,21.82,44-1.35,84.87-32.33,94.7-75.66"/>
                    <path class="cls-wave" d="M1154.37,1401.79c-9.78,43.09-59.84,73.1-102.31,56.36-43.34-17.08-67.55-69.53-120-65.68-18.65,1.37-32.13,11.8-42.87,26.49-13.76,18.82-38.08,49.58-65.87,35-18.53-9.76-21.09-37.55-34.67-52.62-11.54-12.82-27.38-21.64-44.08-25.67-38.83-9.37-69.54,7.11-94,36.36-16.77,20-33.52,39.64-62.2,37.6-31.25-2.21-44.11-28.77-63.51-48.64-27-27.65-70.4-35-104.23-15-17.46,10.34-27.7,27.6-41.21,42.12-22.68,24.36-51.33,22.41-74.4,0-29.14-28.34-54.31-62.14-99-39.75-22.83,11.45-40.21,33.32-65.12,40.75-28,8.36-57.55-2.6-75.83-24.67"/>
                </svg>
            </div>
            <div class="buffer" v-show="playlistVisible"></div>
            <div class="playlist-wrapper" v-show="playlistVisible">
                <div class="add-or-queue-wrapper" v-show="playlistVisible">
                    <div class="add-song selected">Added</div>
                    <div class="que-song">Queue Up</div>
                </div>
                <div class="playlist-songs-section" v-show="!songSearched">
                    <div v-for="song in room_songs" class="playlist-song-wrapper">
                        <div class="playlist-song-info">
                            <span class="song-title" @click="playSelectedSong(song.id)">{{ song.title }}</span>
                            <span class="song-artist">{{song.artist_title}}</span>
                            <h6>Added by {{song.added_by}}</h6>
                        </div>
                        <span class="playlist-song-delete" @click="deleteSong(song)">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 135.06 135.06">
                                <rect class="cls-remove" x="30.03" y="54.53" width="75" height="26" rx="3"/>
                                <circle class="cls-remove-circle" cx="67.53" cy="67.53" r="64.53"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <spotify-web-player
                        :accessToken="access_token"
                        :roomName="name"
                        :roomKey="rkey"
                        @deviceId="storePlayerId"
                        @next="next"
                        @player_state_changed="playerStateChanged"
                        v-on:player-ready="enablePlay()"
                        class="hidden"
                >
                </spotify-web-player>
            </div>
        </div>
        <div class="search-wrapper" :class="{searchingWrapper : songSearched}">
            <div class="search-bar">
                <input type="search" v-model="songName" class="search-input"  @keydown.enter="searchSongs(songName)" @keyup.enter="$event.target.blur()" />
                <div class="search-icon" @click="searchSongs(songName)">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 111.32 124.77">
                        <path class="cls-search" d="M88.42,80.32a4.31,4.31,0,0,1-.31-4.81s4.56-7,6.35-12.71A48.33,48.33,0,1,0,33.88,94.46C44.45,97.77,62,94.3,62,94.3A5.63,5.63,0,0,1,66.87,96l23.46,27.7a3,3,0,0,0,4.22.35l15.71-13.3a3,3,0,0,0,.35-4.23ZM47.55,75.41A28.46,28.46,0,1,1,76,47,28.45,28.45,0,0,1,47.55,75.41Z"/>
                    </svg>
                </div>
                <div class="close-search-wrapper" @click="closeSearch()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64.99 64.65">
                        <path class="cls-exit" d="M64.25,48.46A3,3,0,0,0,64,44.23l-12.52-11A3,3,0,0,1,51.19,29L62,16.65a3,3,0,0,0-.27-4.23L48.48.74A3,3,0,0,0,44.25,1L33.4,13.36a3,3,0,0,1-4.23.27l-12.52-11a3,3,0,0,0-4.23.28L.74,16.19A3,3,0,0,0,1,20.43l12.51,11a3,3,0,0,1,.27,4.24L3,48a3,3,0,0,0,.27,4.23L16.51,63.91a3,3,0,0,0,4.24-.27L31.59,51.29A3,3,0,0,1,35.83,51L48.34,62a3,3,0,0,0,4.24-.27Z"/>
                    </svg>
                </div>
            </div>
            <div class="add-or-queue-wrapper" v-show="songSearched">
                <div class="add-song selected">Add</div>
                <div class="que-song">Queue Up</div>
            </div>
            <div class="searched-songs-wrapper" v-show="songSearched">
                <div v-for="item in songs"  class="song-wrapper">
                    <div class="song-info">
                        <span class="song-title">{{item.name}}</span>
                        <div class="song-artist">{{ item.album.artists[0].name }} <span class="song-added-by"> | {{item.added_by}}</span></div>
                    </div>
                    <div @click="addSong(rkey, item)" class="song-item search-song">
                        <div class="add-remove-songs-wrapper">
                            <div v-if="songIsNotAdded(item)" class="add-song-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 135.06 135.06">
	                                <path class="cls-add" d="M105.41,57.53a3,3,0,0,0-3-3H83.53a3,3,0,0,1-3-3V32.91a3,3,0,0,0-3-3h-20a3,3,0,0,0-3,3V51.53a3,3,0,0,1-3,3H32.66a3,3,0,0,0-3,3v20a3,3,0,0,0,3,3H51.53a3,3,0,0,1,3,3v18.63a3,3,0,0,0,3,3h20a3,3,0,0,0,3-3V83.53a3,3,0,0,1,3-3h18.88a3,3,0,0,0,3-3Z"/>
	                                <circle class="cls-add-circle" cx="67.53" cy="67.53" r="64.53"/>
                                </svg>
                            </div>
                            <div v-else class="remove-song-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 135.06 135.06">
                                    <rect class="cls-remove" x="30.03" y="54.53" width="75" height="26" rx="3"/>
                                    <circle class="cls-remove-circle" cx="67.53" cy="67.53" r="64.53"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
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
                playerId: '',
                currentSong: {},
                queue: [],
                room_songs: JSON.parse(this.raw_room_songs),
                playSong: true,
                searchInputVisible: false,
                added: false,
                songSearched: false,
                playNotDisabled: false,
                showSongDetails: false,
                playlistVisible: false,
                currentSongVisible: false
            }
        },

        props : [
            'username',
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
                this.scrollSearch();
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

                axios.post('/room/' + room + '/song', {song: addedSong, username: this.username}).then((response) => {
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

            scrollSearch(){
                let search = document.getElementsByClassName('search-wrapper');
                search[0].scrollTo(0,search[0].scrollHeight);
            },

            showPlaylist() {
                this.playlistVisible = !this.playlistVisible;
            },
            showCurrentSong() {
                axios.get('/spotify/currently-playing').then(response => {
                    this.currentSong = response.data;
                    this.currentSongVisible = !this.currentSongVisible;
                });
            }
        }
    }
</script>
