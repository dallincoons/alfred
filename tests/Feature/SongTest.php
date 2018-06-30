<?php

use App\Gateways\SpotifyGatewayInterface;
use App\Room;
use Illuminate\Support\Collection;
use Tests\TestCase;

class SongTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function it_searches_songs()
    {
        $room = factory(Room::class)->create();

        $response = $this->get('spotify/songs?q=caroline&room=' . $room->getKey());

        $response->assertSuccessful();
        $this->assertGreaterThan(0 , count($response->decodeResponseJson()));
    }

    /** @test */
    public function it_searches_and_marks_songs_as_added()
    {
        $this->app->singleton(SpotifyGatewayInterface::class, \App\Gateways\SpotifyGateway::class);

        $room = factory(\App\Room::class)->create([
            'playlistId' => '1zW7jv26GVYGQx2W54QRY8'
        ]);

        $addedSong = array_first(data_get(app(SpotifyGatewayInterface::class)->search('dirty nil'), 'tracks.items'));
        $externalSong = (new \App\Gateways\ExternalSong(json_decode(json_encode($addedSong), true)));

        $room->addSong($externalSong);

        $response = $this->get('spotify/songs?q=dirty+nil&room=' . $room->getKey());

        $addedSong = collect($response->decodeResponseJson())->first(function($song) use ($externalSong) {
            return $song['id'] == $externalSong->getId();
        });

        $notAddedSong = collect($response->decodeResponseJson())->first(function($song) use ($externalSong) {
            return $song['id'] !== $externalSong->getId();
        });

        $response->assertSuccessful();
        $this->assertTrue($addedSong['isAdded']);
        $this->assertFalse($notAddedSong['isAdded']);
    }
}
