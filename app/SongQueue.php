<?php

namespace App;

use Illuminate\Support\Facades\Session;

class SongQueue
{
    public static  function play(string $playlistId, array $songs)
    {
        if(empty(Session::get('playlist:' . $playlistId))) {
            Session::put('playlist:' . $playlistId, $songs);
        }
        $playlist = Session::get('playlist:' . $playlistId);
        $song = array_pop($playlist);
        Session::put('playlist:' . $playlistId, $playlist);
        return $song;
    }

    public static function addSong(string $playlistId, string $songId)
    {
        $songQueue = Session::get('playlist:' . $playlistId, []);
        array_push($songQueue, $songId);
        shuffle($songQueue);
        Session::put('playlist:' . $playlistId, $songQueue);
    }
}
