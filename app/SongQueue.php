<?php

namespace App;

use App\Session;

class SongQueue
{
    public static function all(string $playlistIs)
    {
        return \Session::get('playlist:' . $playlistIs);

    }

    public static function play(string $playlistId, array $songs)
    {
        shuffle($songs);

        if(empty(\Session::get('playlist:' . $playlistId))) {
            \Session::put('playlist:' . $playlistId, $songs);
        }
        return self::next($playlistId);
    }

    public static function addSong(string $sessionName, string $songId)
    {
        $songQueue = \Session::get($sessionName, []);
        array_push($songQueue, $songId);
        shuffle($songQueue);
        \Session::put($sessionName, $songQueue);
        return $songQueue;
    }

    public static function next($playlistId)
    {
        $playlist = \Session::get('playlist:' . $playlistId);
        $song = array_pop($playlist);
        \Session::put('playlist:' . $playlistId, $playlist);
        return $song;
    }
}
