<?php

namespace Tests\Feature;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\Room;
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

    /** @test */
    public function user_can_join_already_created_room()
    {
        $room = $this->user->createRoom('test1337');

        $roomCode = $room->share();

        $this->assertFalse($room->join('INVALID'));
        $this->assertTrue($room->join($roomCode));
    }

    /** @test */
    public function room_can_be_shared()
    {
        $roomId = $this->user->createRoom('test1337')->share();
        $roomId2 = $this->user->createRoom('test1338')->share();

        $this->assertTrue(is_string($roomId));
        $this->assertTrue(is_string($roomId2));
        $this->assertNotEquals($roomId, $roomId2);
    }

    /** @test */
    public function room_can_be_joined()
    {
        /** @var Room $room */
        $room = $this->user->createRoom('test1337');
        $roomId = $room->share();

        $this->assertTrue($room->join($roomId));
        $this->assertFalse($room->join('INVALID'));
    }
}
