<?php

namespace App\Gateways;

use App\SpotifyUser;

interface SpotifyGatewayInterface
{
    public function login(SpotifyUser $spotifyUser);
    public function createPlaylist(string $name, string $userId = null);
}
