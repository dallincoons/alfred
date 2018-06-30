<template>
    <div class="flex flex-col bg-gradient h-screen items-center" id="welcome-page">
        <div>
            <div class="account-img">
                <img :src="user.profile_image">
            </div>
            <h2 class="account-username">{{user.name}} <span><a href="/logout" class="logout-link">Logout</a></span></h2>
        </div>
        <div class="account-options">
            <button class="account-option" @click="showRooms()" :class="{active: roomsVisible}">My Rooms</button>
            <button class="account-option" @click="showCreate()" :class="{active: createVisible}">Create Room</button>
            <button class="account-option" @click="showJoin()" :class="{active: joinVisible}">Join Room</button>
        </div>
        <div class="account-content-wrapper">
            <div class="rooms-wrapper" v-show="roomsVisible">
                <ul class="rooms-list">
                    <li v-for="room in user.rooms"><a :href="'/rooms/' + room.id" class="room-link">{{room.name}}</a></li>
                </ul>
            </div>
            <div class="room-action-wrapper" v-show="createVisible">
                <form action="rooms" method="POST">
                    <input type="hidden" name="_token" :value="csrf">
                    <input name="room" placeholder="Name Your Room" class="room-input"/>
                    <input type="submit" value="Create Room" class="room-button">
                </form>
            </div>
            <div class="room-action-wrapper" v-show="joinVisible">
                <form action="room/join" method="POST">
                    <input type="hidden" name="_token" :value="csrf">
                    <input name="room" placeholder="ROOM CODE" class="room-input"/>
                    <input type="submit" value="Join" class="room-button"/>
                </form>
            </div>
        </div>

    </div>

</template>
<script>
    export default {
        data() {
            return {
                user : {},
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                roomsVisible : false,
                createVisible : true,
                joinVisible : false,
            }
        },

        props : [
            'raw_user',
        ],
        created() {
            this.user = JSON.parse(this.raw_user);
            console.log(this.user);
        },

        methods : {
            showRooms() {
                this.roomsVisible = true;
                this.createVisible = false;
                this.joinVisible = false;
            },
            showCreate() {
                this.roomsVisible = false;
                this.createVisible = true;
                this.joinVisible = false;
            },
            showJoin() {
                this.roomsVisible = false;
                this.createVisible = false;
                this.joinVisible = true;
            }
        }
    }
</script>
