<?php

namespace App\Gateways;

class SpotifyGateway implements SpotifyGatewayInterface
{
    /**
     * @var \SpotifyWebAPI\SpotifyWebAPI
     */
    private $api;

    public function __construct()
    {
        $this->api = new \SpotifyWebAPI\SpotifyWebAPI();

        if(\Auth::check()) {
            $this->api->setAccessToken(\Auth::user()->access_token);
        }
    }

    public function createPlaylist(string $name, string $userId = null)
    {
        $result = $this->api->createUserPlaylist($userId ?? \Auth::user()->spotify_id, [
            'name' => $name
        ]);

        return $result->id;
    }

    public function addSong( string $playListId, string $songId, string $userId = null)
    {
        $result = $this->api->addUserPlaylistTracks($userId ?? \Auth::user()->spotify_id, $playListId, $songId);

        return $result;
    }

    public function search(string $searchText)
    {
        return $this->api->search($searchText, 'track');
    }

    public function pause(string $deviceId)
    {
        return $this->api->pause($deviceId);
    }

    public function getPlaylistTracks(string $playlistId)
    {
        return $this->api->getUserPlaylistTracks(\Auth::user()->spotify_id, $playlistId);
    }

    public function getDevices()
    {
        return $this->api->getMyDevices();
    }

    public function changeDevice(string $deviceId): bool
    {
        return $this->api->changeMyDevice(['device_ids' => $deviceId]);
    }

    public function startPlaylist(string $devideId, string $playlistId)
    {
        return $this->api->play($devideId, ["context_uri" => 'spotify:user:' . \Auth::user()->spotify_id .':playlist:' . $playlistId]);
    }

    public function startSong(string $devideId, $songIds)
    {
        $songIds = array_wrap($songIds);

        return $this->api->play($devideId, ["uris" => $songIds]);
    }

    public function resumeSong(string $devideId)
    {
        return $this->api->play($devideId);
    }

    public function currentlyPlayingSong(): Song
    {
        return new Song($this->api->getMyCurrentTrack());
    }

    public function getMyCurrentPlaybackInfo()
    {
        return $this->api->getMyCurrentPlaybackInfo();
    }

    public function next(string $deviceId)
    {
        return $this->api->next($deviceId);
    }
}
