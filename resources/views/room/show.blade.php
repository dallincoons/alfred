@extends('layouts.app')

@section('content')
    <h1>{{$room->name}}</h1>

    <span>Add Song</span>
    <input type="text" v-model="songName"/>
    <button @click="searchSongs(songName)">Search</button>

    <div v-for="song in songs">
        <div v-for="item in song.items">
            <span @click="addSong({{$room->getKey()}}, item.id)">@{{item.name}} - @{{ item.album.artists[0].name }}</span>
        </div>
    </div>
@endsection
