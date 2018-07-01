<?php

namespace Tests\Fakes;

use App\Gateways\ExternalSong;
use App\Gateways\Song;
use App\Gateways\SpotifyGatewayInterface;
use App\GuestUser;
use App\Spotify;
use App\SpotifyUser;
use App\User;

//@todo create song class fixture
class FakeSpotifyGateway implements SpotifyGatewayInterface
{
    public $playlists = [];

    /**
     * @var array
     */
    private $currentlyPlaying;
    private $isPlaying = true;

    public function login(SpotifyUser $spotifyUser)
    {
        return Spotify::login($spotifyUser);
    }

    public function createPlaylist(string $name, string $userId = null)
    {
        $this->playlists[$id = random_int(1000, 9999)] = (object) ['name' => $name, 'songs' => []];

        return $id;
    }

    public function addSong(string $playListId, string $songId, string $userId = null)
    {
        if(!isset($this->playlists[$playListId])) {
            $this->playlists[$playListId] = (object) ['songs' => []];
        }

        array_push($this->playlists[$playListId]->songs, new Song([
            'item' => [
                'album' => [
                    'images' => [
                        0 => (object) [
                            'url' => 'someimage'
                        ]
                    ],
                    'artists' => [
                        0 => [
                            'name' => 'Desmond Dekker'
                        ]
                    ]
                ],
                'id' => $songId,
                'uri' => 'spotify:track:' . $songId,
                'name' => str_random(10),
                'duration_ms' => 1233
            ],
        ]));
    }

    public function search(string $searchText)
    {
        return (object) [
            'tracks' => [
                'items' => [
                    0 => [
                        'duration_ms' => 1234,
                        'id' => '12345678',
                        'name' => str_random(10),
                        'album' => [
                            'images' => [
                                0 => (object) [
                                    'url' => 'someimage'
                                ]
                            ],
                            'artists' => [
                                0 => [
                                    'name' => str_random(10)
                                ]
                            ]
                        ]
                    ],
                    1 => [
                        'duration_ms' => 1234,
                        'id' => '12345679',
                        'name' => str_random(10),
                        'album' => [
                            'images' => [
                                0 => (object) [
                                    'url' => 'someimage'
                                ]
                            ],
                            'artists' => [
                                0 => [
                                    'name' => str_random(10)
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
        return collect($this->playlists[$playlistId]->songs)
            ->map(function($song) {
                return new ExternalSong($song->raw());
            });
    }

    public function changeDevice(string $deviceId): bool
    {
        return true;
    }

    public function startPlaylist(string $deviceId, string $playlistId)
    {
        $this->currentlyPlaying = array_first($this->playlists[$playlistId]->songs);
        return true;
    }

    public function startSong(string $deviceId, $songIds)
    {
        $this->currentlyPlaying = new Song([
            'item' => [
                'id' => array_wrap($songIds)[0],
                'uri' => 'spotify:track:' . array_wrap($songIds)[0]
            ]
        ]);
        return true;
    }

    public function pause(string $deviceId)
    {
        $this->isPlaying = false;
        return true;
    }

    public function resumeSong(string $deviceId)
    {
        $this->isPlaying = true;
        return true;
    }

    public function currentlyPlayingSong(): Song
    {
        return new Song([
            'item' => [
                'album' => [
                    'artists' => [
                        0 => [
                            'name' => 'Desmond Dekker'
                        ]
                    ]
                ],
                'id' => str_after($this->currentlyPlaying->uri(), 'spotify:track:'),
                'uri' => $this->currentlyPlaying->id()
            ],
        ]);
    }

    public function getMyCurrentPlaybackInfo()
    {
        return (object)[
            'is_playing' => $this->isPlaying
        ];
    }

    public function next(string $deviceId)
    {
        $this->currentlyPlaying = (array_last(array_first($this->playlists)->songs));
    }

    public function delete(string $userId, string $playlistId, string $songId)
    {
        $this->playlists[$playlistId]->songs = collect($this->playlists[$playlistId]->songs)->reject(function($song) use ($songId) {
            return $song->id() == $songId;
        })->all();
    }
}
