<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function show(Room $room)
    {
        return view('room.show', compact('room'));
    }

    public function store(Request $request)
    {
        $room = \Auth::user()->createRoom($request->room_name);

        return redirect('rooms/' . $room->getKey());
    }
}
