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

        return response()->json($song);
    }

    public function delete(Request $request, Room $room, int $songId)
    {
        try {
            $room->songs()->find($songId)->delete();
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }

        return response()->json('success', 200);
    }
}
