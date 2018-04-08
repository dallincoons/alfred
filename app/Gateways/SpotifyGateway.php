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
        $this->api->setAccessToken(\Auth::user()->access_token);
    }

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
        } else {
            $user->fill([
                'access_token' => $spotifyUser->access_token,
                'refresh_token' => $spotifyUser->refresh_token,
            ])->save();
        }

        auth()->login($user);

        return $user;
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

    public function getPlaylistTracks(string $playlistId)
    {
        return $this->api->getUserPlaylistTracks(\Auth::user()->spotify_id, $playlistId);
    }
}
