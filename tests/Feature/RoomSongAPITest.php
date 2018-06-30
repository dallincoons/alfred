<?php

use App\Gateways\SpotifyGatewayInterface;
use App\Room;
use App\Song;
use Tests\Fakes\ExternalSongFaker;
use Tests\TestCase;

class RoomSongAPITest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /**
     * @covers \App\Http\Controllers\RoomSongController::store
     *
     * @test
     */
    public function it_adds_song_to_room()
    {
        $this->withoutExceptionHandling();

        $gateway = app(SpotifyGatewayInterface::class);

        $id = $gateway->createPlaylist('1234');

        $room = factory(Room::class)->create([
            'playlistId' => $id
        ]);

        $response = $this->post('room/' . $room->getKey() . '/song', ['song' => ExternalSongFaker::validParams()]);

        $response->assertSuccessful();

        $song = Song::first();

        $this->assertEquals($song->duration, 123456);
        $this->assertEquals($song->big_image, 'some_image.jpg');
    }

    /** @test */
    public function remove_song_from_room()
    {
        /** @var Room $room */
        $room = factory(Room::class)->create();

        $room->addSong(ExternalSongFaker::any());

        $song = $room->songs->first();

        $this->delete('/room/' . $room->getKey() . '/' . $song->getKey());

        $this->assertFalse($room->songs->fresh()->contains($song));
    }
}
