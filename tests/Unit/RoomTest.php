<?php

namespace Tests\Feature;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\GuestUser;
use App\Room;
use Tests\TestCase;

class RoomTest extends TestCase
{
    /** @test */
    public function creating_room_makes_new_playlist()
    {
        $room = $this->user->createRoom('test1337');

        $gateway = app(SpotifyGatewayInterface::class);

        $this->assertArrayHasKey($room->playlistId, $gateway->playlists);
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

    /** @test */
    public function user_can_login_using_room_code()
    {
        /** @var Room $room */
        $room = $this->user->createRoom('test1337');
        $roomId = $room->share();

        $guestUser = GuestUser::where('parent_user_id', $this->user->getKey())->first();

        $room->join($roomId);

        $this->assertEquals($guestUser, \Auth::user());
    }

    /** @test */
    public function user_can_add_songs_to_room()
    {
        /** @var Room $room */
        $room = $this->user->createRoom('test1337');
        $roomId = $room->share();

        $room->join($roomId);

        $room->addSong('60SJRvzXJnVeVfS4RiH14u?si=r7lJ_QeWQIu0c8gvsVvTYg');

        $this->assertEquals('60SJRvzXJnVeVfS4RiH14u?si=r7lJ_QeWQIu0c8gvsVvTYg', $room->songs->first()->external_id);
    }

    /** @test */
    public function user_can_add_multiple_songs_to_room()
    {
        /** @var Room $room */
        $room = $this->user->createRoom('test1337');
        $roomId = $room->share();

        $room->join($roomId);

        $room->addSong('123');
        $room->addSong('456');

        $this->assertEquals(2, count($room->songs));
    }
}
