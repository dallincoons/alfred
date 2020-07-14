<?php

namespace App\Http\Controllers;

use App\Events\SongAdded;
use App\Gateways\ExternalSong;
use App\Room;
use App\Song;
use Illuminate\Http\Request;

class RoomSongController extends Controller
{
    public function store(Request $request, Room $room)
    {
        $externalSong = new ExternalSong($request->input('song'));

        $song = $room->addSong($externalSong, \Session::get('guest_name', 'Guest'));

        return response()->json($song);
    }

    public function delete(Request $request, Room $room, Song $song)
    {
        try {
            $room->removeSong($song->external_id);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

        return response()->json($room->songs, 200);
    }
}
