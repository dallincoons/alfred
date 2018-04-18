<?php

namespace App\Gateways;

use App\GuestUser;
use App\SpotifyUser;
use App\User;

class SpotifyGateway implements SpotifyGatewayInterface
{
    /**
     * @var \SpotifyWebAPI\SpotifyWebAPI
     */
    private $api;

    public function __construct()
    {
        $this->api = new \SpotifyWebAPI\SpotifyWebAPI();
    }

    public function createPlaylist(string $name, string $userId = null)
    {
        $this->api->setAccessToken(\Auth::user()->access_token);

        $result = $this->api->createUserPlaylist($userId ?? \Auth::user()->spotify_id, [
            'name' => $name
        ]);

        return $result->id;
    }

    public function addSong( string $playListId, string $songId, string $userId = null)
    {
        $this->api->setAccessToken(\Auth::user()->access_token);

        $result = $this->api->addUserPlaylistTracks($userId ?? \Auth::user()->spotify_id, $playListId, $songId);

        return $result;
    }

    public function search(string $searchText)
    {
        $this->api->setAccessToken(\Auth::user()->access_token);

        return $this->api->search($searchText, 'track');
    }

    public function getPlaylistTracks(string $playlistId)
    {
        $this->api->setAccessToken(\Auth::user()->access_token);

        return $this->api->getUserPlaylistTracks(\Auth::user()->spotify_id, $playlistId);
    }

    public function getDevices()
    {
        $this->api->setAccessToken(\Auth::user()->access_token);

        return $this->api->getMyDevices();
    }

    public function changeDevice(string $deviceId): bool
    {
        $this->api->setAccessToken(\Auth::user()->access_token);

        return $this->api->changeMyDevice(['device_ids' => $deviceId]);
    }

    public function startPlaylist(string $devideId, string $playlistId)
    {
        $this->api->setAccessToken(\Auth::user()->access_token);

        return $this->api->play($devideId, ["context_uri" => 'spotify:user:' . \Auth::user()->spotify_id .':playlist:' . $playlistId]);
    }

    public function startSong(string $devideId, $songIds)
    {
        $songIds = array_wrap($songIds);

        $this->api->setAccessToken(\Auth::user()->access_token);

        return $this->api->play($devideId, ["uris" => $songIds]);
    }

    public function currentlyPlayingSong()
    {
        $this->api->setAccessToken(\Auth::user()->access_token);

        return $this->api->getMyCurrentTrack();
    }
}
