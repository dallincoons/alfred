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

    /** @test */
    public function it_searches_for_songs()
    {
        $spotifyUser = new SpotifyUser(123456789, 'Paul M', '1111', '22222');
        $this->getGateway()->login($spotifyUser);

        $result = $this->getGateway()->search('summertime teenage bottlerocket');

        $image = data_get($result, 'tracks.items.0.album.images.0');

        $this->assertTrue(is_string(data_get($result, 'tracks.items.0.id')));
        $this->assertTrue(is_string($image->url));
    }

    public function getPlaylistTracks(string $playlistId)
    {
        return $this->playlists[$playlistId]->songs;
    }
}
