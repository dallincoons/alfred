<?php

namespace Tests\Feature;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\GuestUser;
use App\Room;
use App\Spotify;
use App\User;
use Illuminate\Support\Facades\Session;
use Tests\Fakes\ExternalSongFaker;
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

        $guestUser = GuestUser::where('parent_id', $this->user->getKey())->first();

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

        $room->addSong(ExternalSongFaker::any());

        $this->assertEquals('1234', $room->songs->first()->external_id);
        $this->assertEquals('Bummer Deal', $room->songs->first()->artist_title);
        $this->assertEquals('Ties That Bind', $room->songs->first()->title);
    }

    /** @test */
    public function user_can_add_multiple_songs_to_room()
    {
        /** @var Room $room */
        $room = $this->user->createRoom('test1337');
        $roomId = $room->share();

        $room->join($roomId);

        $room->addSong(ExternalSongFaker::withId('123'));
        $room->addSong(ExternalSongFaker::withId('456'));

        $this->assertEquals(2, count($room->songs));
    }

    /** @test */
    public function plays_songs_in_order_as_stored_in_session()
    {
        /** @var Room $room */
        $room = factory(Room::class)->create();
        $room->addSong(ExternalSongFaker::withId('46ty9wS8la1HWGndeqep7k'));
        $room->addSong(ExternalSongFaker::withId('0fgCZv9soFDMFNOOmZ8kck'));

        $songs = ['spotify:track:46ty9wS8la1HWGndeqep7k', 'spotify:track:0fgCZv9soFDMFNOOmZ8kck'];
        $room->play('82c86b09fbd6826211f9223a3480f455c65ea17b');
        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong()->id();

        $this->assertContains($currentSong, $songs);

        $lastSong = array_first(array_except($songs, array_search($currentSong, $songs)));
        $room->play('82c86b09fbd6826211f9223a3480f455c65ea17b');
        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong();

        $this->assertEquals($lastSong, $currentSong->id());

        $songs = ['spotify:track:46ty9wS8la1HWGndeqep7k', 'spotify:track:0fgCZv9soFDMFNOOmZ8kck'];
        $room->play('82c86b09fbd6826211f9223a3480f455c65ea17b');
        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong()->id();

        $this->assertContains($currentSong, $songs);
    }

    /** @test */
    public function it_plays_only_songs_for_the_current_room()
    {
        $room = factory(Room::class)->create();
        $room->addSong(ExternalSongFaker::withId('46ty9wS8la1HWGndeqep7k'));
        $room->addSong(ExternalSongFaker::withId('0fgCZv9soFDMFNOOmZ8kck'));

        $room2 = factory(Room::class)->create();
        $room2->addSong(ExternalSongFaker::withId('6aiEz7VcFWKbmpL0Q8nxYC'));
        $room2->addSong(ExternalSongFaker::withId('6aiEz7VcFWKbmpL0Q8nxYC'));

        $room->play('82c86b09fbd6826211f9223a3480f455c65ea17b');
        $currentSong =app(SpotifyGatewayInterface::class)->currentlyPlayingSong()->id();

        $this->assertContains($currentSong, ['spotify:track:46ty9wS8la1HWGndeqep7k', 'spotify:track:0fgCZv9soFDMFNOOmZ8kck']);

        $room2->play('82c86b09fbd6826211f9223a3480f455c65ea17b');
        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong()->id();

        $this->assertContains($currentSong, ['spotify:track:6aiEz7VcFWKbmpL0Q8nxYC', 'spotify:track:1YAXrOLk7EGfv1tlSnGOqi']);
    }

    /** @test */
    public function adding_a_song_refreshes_session_data()
    {
        $room = factory(Room::class)->create();
        $room->addSong(ExternalSongFaker::withId('46ty9wS8la1HWGndeqep7k'));
        $room->addSong(ExternalSongFaker::withId('0fgCZv9soFDMFNOOmZ8kck'));

        $this->assertContains('46ty9wS8la1HWGndeqep7k', Session::get('playlist:' . $room->playlistId));
        $this->assertContains('0fgCZv9soFDMFNOOmZ8kck', Session::get('playlist:' . $room->playlistId));
    }
}
