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

    public function play(Room $room, string $deviceId)
    {
        $success = $room->play($deviceId);

        return response()->json($success);
    }

    public function pause(Request $request, Room $room)
    {
        $success = $room->pause($request->input('device_id'));

        return response()->json($success);
    }

    public function resume(Request $request, Room $room)
    {
        $success = $room->resume($request->input('device_id'));

        return response()->json($success);
    }
}
