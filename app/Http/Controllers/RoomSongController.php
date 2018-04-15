<?php

namespace App\Http\Controllers;

use App\Room;

class RoomSongController extends Controller
{
    public function store(Room $room, string $songId)
    {
        $room->addSong($songId);

        return response();
    }
}
