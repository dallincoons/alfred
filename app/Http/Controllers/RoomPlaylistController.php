<?php

namespace App\Http\Controllers;

use App\Gateways\SpotifyGatewayInterface;
use App\Room;
use Illuminate\Http\Request;

class RoomPlaylistController extends Controller
{
    /**
     * @var SpotifyGatewayInterface
     */
    private $spotify;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->spotify = app(SpotifyGatewayInterface::class);

            return $next($request);
        });
    }

    public function play(Room $room)
    {
        $success = $room->player()->play($room->songs()->pluck('external_id')->all());

        return response()->json($success);
    }

    public function pause(Request $request, Room $room)
    {
        $success = $room->pause();

        return response()->json($success);
    }

    public function resume(Request $request, Room $room)
    {
        $success = $room->resume();

        return response()->json($success);
    }

    public function next(Request $request, Room $room)
    {
        $success = $room->next();

        return response()->json($success);
    }

    public function device(Request $request, Room $room)
    {
        \Session::forget('player');
        \Session::forget('playlist:' . $room->playlistId);
        $room->storeDeviceId($request->input('device_id'));
    }
}
