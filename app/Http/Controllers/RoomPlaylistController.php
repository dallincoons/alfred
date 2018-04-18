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

    public function __construct(SpotifyGatewayInterface $spotifyGateway)
    {
        $this->spotify = $spotifyGateway;
    }

    public function update(Room $room, string $deviceId)
    {
        $success = $room->play($deviceId);

        return response()->json($success);
    }
}
