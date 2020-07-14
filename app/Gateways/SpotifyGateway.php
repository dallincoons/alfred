<?php

namespace App\Gateways;

use SpotifyWebAPI\SpotifyWebAPIException;

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

            $this->checkAccessToken();
        }
    }

    protected function checkAccessToken()
    {
        $session = new \SpotifyWebAPI\Session(
            config('services.spotify.client_id'),
            config('services.spotify.client_secret'),
            config('services.spotify.redirect')
        );

        try {
            //@todo get rid of this
            if(!\App::runningUnitTests()) {
                $this->api->me();
            }
        } catch (SpotifyWebAPIException $e) {
            $session->refreshAccessToken(\Auth::user()->refresh_token);
            $user = \Auth::user();
            $user->access_token = $session->getAccessToken();
            if ($session->getRefreshToken()) {
                $user->refresh_token = $session->getRefreshToken();
            }
            $user->save();
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
        return collect($this->api->getUserPlaylistTracks(\Auth::user()->spotify_id, $playlistId)->items)
            ->map(function($songInfo) {
                return new ExternalSong($songInfo->track);
            });
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

    public function shuffle(array $options)
    {
        return $this->api->shuffle($options);
    }

    public function queueSong(string $songUri)
    {
        return $this->api->queue($songUri);
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

    public function previous(string $deviceId)
    {
        return $this->api->previous($deviceId);
    }

    public function delete(string $userId, string $playlistId, string $songId)
    {
        $this->api->deleteUserPlaylistTracks($userId, $playlistId, [(object)['id' => $songId]]);
    }
}
