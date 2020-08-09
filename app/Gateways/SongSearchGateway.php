<?php

namespace App\Gateways;

use App\Room;

class SongSearchGateway
{
    /**
     * @var SpotifyGatewayInterface
     */
    private $client;

    public function __construct(SpotifyGatewayInterface $client)
    {
        $this->client = $client;
    }

    public function searchSongs(string $q, string $room)
    {
        $result = $this->client->search($q);

        $room = Room::findOrFail($room);

        $songIds = $room->songs->pluck('external_id')->all();

        $rawSongs = data_get($result, 'tracks.items');

        $previouslyAddedSongs = \App\Song::query()
            ->select(['external_id', 'added_by', 'room_song.song_id', 'room_song.room_id'])
            ->join('room_song', 'songs.id', '=', 'room_song.song_id')
            ->where('room_song.room_id', '=', $room->getKey())
            ->whereIn('external_id', $songIds)
            ->get()
            ->keyBy('external_id');

        return collect($rawSongs)
            ->map(function($song) use ($songIds, $previouslyAddedSongs) {
                $externalSong = new PlaylistExternalSong($song, $songIds);

                if (isset($previouslyAddedSongs[$externalSong->getId()])) {
                    $externalSong->setAddedBy($previouslyAddedSongs[$externalSong->getId()]->added_by);
                }

                return $externalSong;
            });
    }
}
