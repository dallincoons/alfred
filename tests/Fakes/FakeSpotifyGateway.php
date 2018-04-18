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
                'uri' => $spotifyUser->uri,
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

    public function search(string $searchText)
    {
        return (object) [
            'tracks' => [
                'items' => [
                    0 => [
                        'id' => '12345678',
                        'album' => [
                            'images' => [
                                0 => (object) [
                                    'url' => 'someimage'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function getPlaylistTracks(string $playlistId)
    {
        return $this->playlists[$playlistId]->songs;
    }

    public function changeDevice(string $deviceId): bool
    {
        return true;
    }

    public function startPlaylist(string $deviceId, string $playlistId)
    {
        return true;
    }

    public function startSong(string $deviceId, array $songIds)
    {
        return true;
    }
}
