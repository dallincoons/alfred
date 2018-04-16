<?php

namespace App\Gateways;

interface SpotifyGatewayInterface
{
    public function createPlaylist(string $name, string $userId = null);
    public function addSong( string $playListId, string $songId, string $userId = null);
    public function search(string $searchText);
    public function getPlaylistTracks(string $playlistId);
    public function changeDevice(string $playlistId): bool;
}
