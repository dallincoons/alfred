<template>
    <div @click="queueSong(songId)" v-show="queueUpVisible" class="search-song-queue">
        <span v-show="!queued">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 135.06 135.06">
                <path class="cls-que" d="M66.29,42c-1.43-.83-2.6-.15-2.6,1.5V50.3a5.77,5.77,0,0,0,2.6,4.5l20.7,12c1.43.82,1.43,2.17,0,3l-20.7,12a5.76,5.76,0,0,0-2.6,4.5v4.14c0,1.65,1.17,2.32,2.6,1.5l40.55-23.41c1.43-.83,1.43-2.18,0-3Z"/>
                <path class="cls-que" d="M28.23,43.22c-1.43-.82-2.6-.15-2.6,1.5V51.5a5.76,5.76,0,0,0,2.6,4.5l20.7,12c1.43.82,1.43,2.17,0,3l-20.7,12a5.76,5.76,0,0,0-2.6,4.5v4.14c0,1.65,1.17,2.32,2.6,1.5L68.78,69.64c1.43-.83,1.43-2.18,0-3Z"/>
                <circle class="cls-que-circle" cx="67.53" cy="67.53" r="64.53"/>
            </svg>
        </span>
        <span v-show="queued">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 135.06 135.06">
                <path class="cls-check" d="M106.76,49.25A3,3,0,0,0,107,45l-6-6.66a3,3,0,0,0-4.23-.22l-42.06,38A3.17,3.17,0,0,1,50.33,76L38.86,64.35a3,3,0,0,0-4.24,0l-6.4,6.3a3,3,0,0,0,0,4.25l18.62,18.9C48,95,48.93,95.9,48.94,95.89s.65.67,1.41,1.51,2.4.64,3.62-.47Z"/>
                <circle class="cls-check-circle" cx="67.53" cy="67.53" r="64.53"/>
            </svg>
        </span>
    </div>
</template>

<script>
    export default {
        name: "queIcon",
        props : [
            'songId'
        ],
        data () {
            return {
                queued: false
            }
        },
        methods : {
            queueSong(song_uri) {
                axios.post('/spotify/queue-song', {
                    song_uri: song_uri,
                }).then(response => {

                });
                this.queued = !this.queued;
            },
        }
    }
</script>

<style scoped>

</style>
