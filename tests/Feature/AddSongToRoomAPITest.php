<?php

use App\Gateways\SpotifyGatewayInterface;
use App\Room;
use Tests\TestCase;

class AddSongToRoomAPITest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function it_adds_song_to_room()
    {
        $gateway = app(SpotifyGatewayInterface::class);

        $id = $gateway->createPlaylist('1234');

        $room = factory(Room::class)->create([
            'playlistId' => $id
        ]);

        $response = $this->post('room/' . $room->getKey() . '/song', ['song' => ['id' => '1234']]);

        $response->assertSuccessful();
    }
}
