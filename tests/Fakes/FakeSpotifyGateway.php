<?php

namespace Tests\Fakes;

use App\Gateways\SpotifyGatewayInterface;
use App\GuestUser;
use App\SpotifyUser;
use App\User;

class FakeSpotifyGateway implements SpotifyGatewayInterface
{
    public $playlists = [];

    public function login(SpotifyUser $spotifyUser)
    {
        $user = User::firstOrNew(['spotify_id' => $spotifyUser->id]);

        if (!$user->exists) {
            $user->fill([
                'name' => $spotifyUser->name,
                'spotify_id' => $spotifyUser->id,
                'access_token' => $spotifyUser->access_token,
                'refresh_token' => $spotifyUser->refresh_token,
            ])->save();

            GuestUser::create([
                'parent_user_id' => $user->getKey()
            ]);
        };

        auth()->login($user);

        return $user;
    }

    public function createPlaylist(string $name, string $userId = null)
    {
        $this->playlists[$id = random_int(1000, 9999)] = (object) ['name' => $name, 'songs' => []];

        return $id;
    }

    public function addSong(string $playListId, string $songId, string $userId = null)
    {
        array_push($this->playlists[$playListId]->songs, $songId);
    }
}
