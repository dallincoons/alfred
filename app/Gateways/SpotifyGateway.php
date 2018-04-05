<?php

namespace App\Gateways;

use App\SpotifyUser;
use App\User;

class SpotifyGateway implements SpotifyGatewayInterface
{
    public function login(SpotifyUser $spotifyUser)
    {
        $user = User::firstOrNew(['spotify_id' => $spotifyUser->id]);

        $user->fill([
            'access_token' => $spotifyUser->access_token,
            'refresh_token' => $spotifyUser->refresh_token,
        ]);

        if (!$user->exists) {
            $user->fill([
                'name' => $spotifyUser->name,
                'spotify_id' => $spotifyUser->id,
            ]);
        };

        $user->save();

        auth()->login($user);

        return $user;
    }

    public function createPlaylist(string $name, string $userId = null)
    {
        $api = new \SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken(env('TEST_SPOTIFY_KEY'));

        $result = $api->createUserPlaylist($userId ?? \Auth::user()->spotify_id, [
            'name' => $name
        ]);

        return $result->id;
    }
}
