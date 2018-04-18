<?php

namespace App\Gateways;

interface SpotifyGatewayInterface
{
    public function createPlaylist(string $name, string $userId = null);
    public function addSong( string $playListId, string $songId, string $userId = null);
    public function search(string $searchText);
    public function getPlaylistTracks(string $playlistId);
    public function changeDevice(string $deviceId): bool;
    public function startPlaylist(string $deviceId, string $playlistId);
    public function startSong(string $deviceId, array $songIds);
}
