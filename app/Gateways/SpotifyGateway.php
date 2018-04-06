<?php

namespace App\Gateways;

use App\GuestUser;
use App\SpotifyUser;
use App\User;

class SpotifyGateway implements SpotifyGatewayInterface
{
    public function login(SpotifyUser $spotifyUser)
    {
        $user = User::firstOrNew(['spotify_id' => $spotifyUser->id]);

        if (!$user->exists) {
            $user->fill([
                'access_token' => $spotifyUser->access_token,
                'refresh_token' => $spotifyUser->refresh_token,
                'name' => $spotifyUser->name,
                'spotify_id' => $spotifyUser->id,
            ])->save();

            GuestUser::create([
                'parent_user_id' => $user->getKey()
            ]);
        };

        $user->fill([
            'access_token' => $spotifyUser->access_token,
            'refresh_token' => $spotifyUser->refresh_token,
        ])->save();

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

    public function addSong( string $playListId, string $songId, string $userId = null)
    {
        $api = new \SpotifyWebAPI\SpotifyWebAPI();
        $api->setAccessToken(env('TEST_SPOTIFY_KEY'));

        $result = $api->addUserPlaylistTracks($userId ?? \Auth::user()->spotify_id, $playListId, $songId);

        return $result;
    }
}
