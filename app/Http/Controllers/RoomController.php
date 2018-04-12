<?php

namespace App\Http\Controllers;

use App\CodeGenerator;
use App\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    public function __construct(CodeGenerator $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;

        $this->middleware('auth', ['only' => ['index','show']]);
    }

    public function show(Room $room)
    {
        $roomCode = $this->codeGenerator->encode($room->getKey());

        return view('room.show', compact('room', 'roomCode'));
    }

    public function store(Request $request)
    {
        $room = \Auth::user()->createRoom($request->room_name);

        return redirect('rooms/' . $room->getKey());
    }
}
