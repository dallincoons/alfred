<?php

namespace Tests\Feature;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use Tests\TestCase;

class RoomTest extends TestCase
{
    /** @test */
    public function creating_room_makes_new_playlist()
    {
        $room = $this->user->createRoom('test1337');

        $gateway = app(SpotifyGatewayInterface::class);

        $this->assertContains('test1337', $gateway->playlists);
        $this->assertEquals(array_search('test1337', $gateway->playlists), $room->playlistId);
    }
}
