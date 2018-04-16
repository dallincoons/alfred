<?php

namespace App\Http\Controllers;

use App\Gateways\SpotifyGatewayInterface;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * @var SpotifyGatewayInterface
     */
    private $spotify;

    public function __construct(SpotifyGatewayInterface $spotifyGateway)
    {
        $this->spotify = $spotifyGateway;
    }

    public function update(string $deviceId)
    {
        $success = $this->spotify->changeDevice($deviceId);

        return response()->json($success);
    }
}
