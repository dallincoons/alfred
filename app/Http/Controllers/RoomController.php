<?php

namespace App\Http\Controllers;

use App\CodeGenerator;
use App\Http\Requests\RoomStoreRequest;
use App\Room;
use App\Session;
use App\Song;
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

        $songs = $room->songs;

        $userId = \Auth::user()->hasParent() ? \Auth::user()->parent_id : \Auth::user()->getKey();

        $songIds = Session::getUserQueue($userId, $room->playlistId);

        $queue = json_encode($songs->filter(function ($song) use ($songIds) {
            return in_array($song->external_id, $songIds);
        })->values());

        return view('room.show', compact('room', 'roomCode', 'songs', 'queue'));
    }

    public function store(RoomStoreRequest $request)
    {
        if(!\Auth::check()) {
            if($request->room) {
                \Session::put('create-room', [
                    'name' => $request->room
                ]);
            }
            return redirect('/connect');
        }

        $room = \Auth::user()->createRoom($request->room);

        return redirect('rooms/' . $room->getKey());
    }
}
