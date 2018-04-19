<?php

namespace Tests\Feature;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\GuestUser;
use App\Room;
use App\Spotify;
use Illuminate\Support\Facades\Session;
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

    /** @test */
    public function plays_songs_in_order_as_stored_in_session()
    {
        /** @var Room $room */
        $room = factory(Room::class)->create();
        $room->addSong('46ty9wS8la1HWGndeqep7k');
        $room->addSong('0fgCZv9soFDMFNOOmZ8kck');

        $songs = ['spotify:track:46ty9wS8la1HWGndeqep7k', 'spotify:track:0fgCZv9soFDMFNOOmZ8kck'];
        $room->play('82c86b09fbd6826211f9223a3480f455c65ea17b');
        $currentSong = array_get(app(SpotifyGatewayInterface::class)->currentlyPlayingSong(), 'item.id');

        $this->assertContains($currentSong, $songs);

        $lastSong = array_first(array_except($songs, array_search($currentSong, $songs)));
        $room->play('82c86b09fbd6826211f9223a3480f455c65ea17b');
        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong();

        $this->assertEquals($lastSong, array_get($currentSong, 'item.id'));

        $songs = ['spotify:track:46ty9wS8la1HWGndeqep7k', 'spotify:track:0fgCZv9soFDMFNOOmZ8kck'];
        $room->play('82c86b09fbd6826211f9223a3480f455c65ea17b');
        $currentSong = array_get(app(SpotifyGatewayInterface::class)->currentlyPlayingSong(), 'item.id');

        $this->assertContains($currentSong, $songs);
    }

    /** @test */
    public function it_plays_only_songs_for_the_current_room()
    {
        $room = factory(Room::class)->create();
        $room->addSong('46ty9wS8la1HWGndeqep7k');
        $room->addSong('0fgCZv9soFDMFNOOmZ8kck');

        $room2 = factory(Room::class)->create();
        $room2->addSong('6aiEz7VcFWKbmpL0Q8nxYC');
        $room2->addSong('1YAXrOLk7EGfv1tlSnGOqi');

        $room->play('82c86b09fbd6826211f9223a3480f455c65ea17b');
        $currentSong = array_get(app(SpotifyGatewayInterface::class)->currentlyPlayingSong(), 'item.id');

        $this->assertContains($currentSong, ['spotify:track:46ty9wS8la1HWGndeqep7k', 'spotify:track:0fgCZv9soFDMFNOOmZ8kck']);

        $room2->play('82c86b09fbd6826211f9223a3480f455c65ea17b');
        $currentSong = array_get(app(SpotifyGatewayInterface::class)->currentlyPlayingSong(), 'item.id');

        $this->assertContains($currentSong, ['spotify:track:6aiEz7VcFWKbmpL0Q8nxYC', 'spotify:track:1YAXrOLk7EGfv1tlSnGOqi']);
    }
}
