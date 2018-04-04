<?php

namespace Tests\Feature;

use App\Gateways\Spotify;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function add_song_to_room_playlist()
    {
        $room = new Room();

        $song = factory(Song::class)->create();

        $room->addSong($song);
    }
}
