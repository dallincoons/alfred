<?php

namespace Tests\Feature;

use App\Gateways\SpotifyGateway;
use App\Gateways\SpotifyGatewayInterface;
use App\GuestUser;
use App\PlayerStateMachine\PlayerMachine;
use App\PlayerStateMachine\PlayerMachineState;
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

        $roomCode = $room->code;

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
        $roomId = $room->code;

        $this->assertTrue($room->join($roomId));
        $this->assertFalse($room->join('INVALID'));
    }

    /** @test */
    public function user_can_login_using_room_code()
    {
        /** @var Room $room */
        $room = $this->user->createRoom('test1337');
        $roomId = $room->code;

        $guestUser = GuestUser::where('parent_id', $this->user->getKey())->first();

        $room->join($roomId);

        $this->assertEquals($guestUser, \Auth::user());
    }

    /** @test */
    public function user_can_add_songs_to_room()
    {
        /** @var Room $room */
        $room = $this->user->createRoom('test1337');
        $roomId = $room->code;

        $room->join($roomId);

        $room->addSong($song = ExternalSongFaker::any());

        $this->assertEquals($song->getId(), $room->songs->first()->external_id);
        $this->assertEquals('Bummer Deal', $room->songs->first()->artist_title);
        $this->assertEquals('Ties That Bind', $room->songs->first()->title);
        $this->assertEquals($this->user->name, $room->songs->first()->added_by);
    }

    /** @test */
    public function guest_user_can_add_songs_to_room()
    {
        /** @var Room $room */
        $room = $this->user->createRoom('test1337');

        \Session::put('guest_name', 'dallin');

        $room->addSong(ExternalSongFaker::any());

        $this->assertEquals('dallin', $room->songs->first()->added_by);
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
        $room->play();
        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong()->id();

        $this->assertContains($currentSong, $songs);

        $nextSong = array_first(array_except($songs, array_search($currentSong, $songs)));

        $room->next();
        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong();

        $this->assertEquals($nextSong, $currentSong->id());

        $songs = ['spotify:track:46ty9wS8la1HWGndeqep7k', 'spotify:track:0fgCZv9soFDMFNOOmZ8kck'];
        $room->next();
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

        $room->play();
        $currentSong =app(SpotifyGatewayInterface::class)->currentlyPlayingSong()->id();

        $this->assertContains($currentSong, ['spotify:track:46ty9wS8la1HWGndeqep7k', 'spotify:track:0fgCZv9soFDMFNOOmZ8kck']);

        $room2->play();
        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong()->id();

        $this->assertContains($currentSong, ['spotify:track:46ty9wS8la1HWGndeqep7k', 'spotify:track:0fgCZv9soFDMFNOOmZ8kck']);
    }

    /** @test */
    public function room_provides_player_state_machine()
    {
        $room = factory(Room::class)->create();

        $this->assertInstanceOf(PlayerMachine::class, $room->player());
    }

    /** @test */
    public function can_play_one_song_when_player_is_idle()
    {
        $room = factory(Room::class)->create();
        $room->addSong($song = ExternalSongFaker::any());

        $this->patch('/room/' . $room->getKey() . '/song/' . $room->songs->first()->getKey() . '/play');

        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong()->id();

        $this->assertEquals($currentSong, 'spotify:track:' . $song->getId());
    }

    /** @test */
    public function can_play_one_song_when_player_is_playing()
    {
        $room = factory(Room::class)->create();
        $room->addSong(ExternalSongFaker::any());
        $room->addSong($song = ExternalSongFaker::any(['id' => 'test32233']));

        $room->play();

        $this->patch('/room/' . $room->getKey() . '/song/' . $room->songs->last()->getKey() . '/play');

        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong()->id();

        $this->assertEquals($currentSong, 'spotify:track:' . $song->getId());
    }

    /** @test */
    public function can_play_one_song_when_player_is_paused()
    {
        $room = factory(Room::class)->create();
        $room->addSong(ExternalSongFaker::any());
        $room->addSong($song = ExternalSongFaker::any(['id' => 'test32233']));

        $room->play();
        $room->pause();

        $this->patch('/room/' . $room->getKey() . '/song/' . $room->songs->last()->getKey() . '/play');

        $currentSong = app(SpotifyGatewayInterface::class)->currentlyPlayingSong()->id();

        $this->assertEquals($currentSong, 'spotify:track:' . $song->getId());
    }
}
