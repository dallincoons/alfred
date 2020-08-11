<?php

namespace App\Http\Controllers;

use App\CodeGenerator;
use App\Events\SongAdded;
use App\Gateways\ExternalSong;
use App\Http\Requests\RoomStoreRequest;
use App\Room;
use App\Song;

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

        $room->sync();

        $songs = $room->songs;

        try {
            SongAdded::dispatch(Song::first());
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }

        return view('room.show', compact('room', 'roomCode', 'songs'));
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
