<?php

namespace App\Http\Controllers;

use App\Room;

class RoomSongController extends Controller
{
    public function store(Room $room, string $song)
    {
        $room->addSong($song);
    }
}
