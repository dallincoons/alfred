<?php

namespace App\Http\Controllers;

use App\Gateways\ExternalSong;
use App\Room;
use Illuminate\Http\Request;

class RoomSongController extends Controller
{
    public function store(Request $request, Room $room)
    {
        $externalSong = new ExternalSong($request->input('song'));

        $room->addSong($externalSong);

        return response()->json();
    }
}
