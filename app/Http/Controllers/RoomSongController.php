<?php

namespace App\Http\Controllers;

use App\Events\SongAdded;
use App\Gateways\ExternalSong;
use App\Room;
use Illuminate\Http\Request;

class RoomSongController extends Controller
{
    public function store(Request $request, Room $room)
    {
        $externalSong = new ExternalSong($request->input('song'));

        $song = $room->addSong($externalSong);

        SongAdded::dispatch($song);

        return response()->json($song);
    }
}
