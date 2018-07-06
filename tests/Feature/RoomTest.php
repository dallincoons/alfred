<?php

use App\CodeGenerator;
use App\Events\SongQueueStarted;
use App\Gateways\SpotifyGatewayInterface;
use App\Room;
use App\Song;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Tests\Fakes\ExternalSongFaker;
use Tests\TestCase;

class RoomTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function it_can_create_room()
    {
        $this->post('/rooms', ['room' => 'test123']);

        $this->assertEquals(1, Room::count());
        $this->assertEquals('test123', Room::first()->name);
    }

    /** @test */
//    public function guest_can_join_room_using_code()
//    {
//        $room = factory(Room::class)->create();
//
//        $response = $this->post('/room/join', ['room' => $room->share()]);
//
//        $response->assertRedirect('/rooms/' . $room->getKey());
//        $this->assertTrue(\Auth::user()->hasParent());
//    }

    /** @test */
    public function room_cannot_be_accessed_unless_authenticated()
    {
        $this->withExceptionHandling();

        $room = factory(Room::class)->create();

        auth()->logout();

        $response = $this->get('/rooms/' . $room->getKey());

        $response->assertRedirect('/');
        $this->assertNull(\Auth::user());
    }

    /** @test */
    public function room_can_be_joined_with_correct_code()
    {
        $room = factory(Room::class)->create();

        auth()->logout();

        $response = $this->post('/room/join', ['room' => $room->code]);

        $response->assertRedirect('/rooms/' . $room->getKey());
        $this->assertEquals(\Auth::user(), ($room->user));
        $this->assertEquals('Guest', \Session::get('guest_name'));

        auth()->logout();

        $this->post('/room/join', ['room' => $room->code, 'name' => 'dallin']);

        $this->assertEquals('dallin', \Session::get('guest_name'));
    }

    /** @test */
    public function room_can_use_arbitrary_string_as_code()
    {
        $room = factory(Room::class)->create([
            'code' => 'h18'
        ]);

        auth()->logout();

        $response = $this->post('/room/join', ['room' => 'h18']);

        $response->assertRedirect('/rooms/' . $room->getKey());
        $this->assertEquals(\Auth::user(), ($room->user));
    }

    /** @test */
    public function room_can_be_joined_with_lower_case_correct_code()
    {
        $room = factory(Room::class)->create();

        auth()->logout();

        $response = $this->post('/room/join', ['room' => $room->code]);

        $response->assertRedirect('/rooms/' . $room->getKey());
        $this->assertEquals(\Auth::user(), ($room->user));
    }

    /** @test */
    public function room_cannot_be_joined_with_incorrect_code()
    {
        factory(Room::class)->create();

        auth()->logout();

        $this->expectExceptionMessage('Invalid room code');
        $this->post('/room/join', ['room' => 'incorrect']);
    }

    /** @test */
    public function user_can_add_songs_to_playlist()
    {
        /** @var Room $room */
        $room = $this->user->createRoom('test1337');
        $roomId = $room->code;

        $room->join($roomId);

        $room->addSong(ExternalSongFaker::withId('60SJRvzXJnVeVfS4RiH14u'));

        $songs = app(SpotifyGatewayInterface::class)->playlists[$room->playlistId]->songs;

        $this->assertContains('60SJRvzXJnVeVfS4RiH14u', array_first($songs)->id());
    }

    /** @test */
    public function store_device_id_for_room()
    {
        $room = factory(Room::class)->create();

        $roomHash = app(CodeGenerator::class)->encode($room->getKey());

        $this->assertEmpty(Session::get('deviceIdForRoom:' . $roomHash));

        $room->storeDeviceId($deviceId = '12345678');

        $this->assertEquals('12345678', $room->getOriginal('deviceId'));
    }

    /** @test */
    public function starting_playback_fires_event()
    {
        Event::fake();

        /** @var Room $room */
        $room = factory(Room::class)->create();

        $room->addSong(ExternalSongFaker::withId('60SJRvzXJnVeVfS4RiH14u'));
        $room->play('12345678');

        Event::assertDispatched(SongQueueStarted::class);
    }

    /** @test */
    public function it_removes_songs_that_were_removed_in_playlist()
    {
        /** @var Room $room */
        $room = factory(Room::class)->create();

        $room->addSong(ExternalSongFaker::any());
        $room->addSong(ExternalSongFaker::any());

        $firstSong = collect(app(SpotifyGatewayInterface::class)->getPlaylistTracks($room->playlistId))->first();

        app(SpotifyGatewayInterface::class)->delete(auth()->user()->spotify_id, $room->playlistId, $firstSong->getId());

        $room->sync();

        $this->assertCount(1, $room->songs);
    }

    /** @test */
    public function it_adds_songs_that_were_added_in_playlist()
    {
        /** @var Room $room */
        $room = factory(Room::class)->create();

        $room->addSong(ExternalSongFaker::any());

//        $firstSong = collect(app(SpotifyGatewayInterface::class)->getPlaylistTracks($room->playlistId))->first();

        app(SpotifyGatewayInterface::class)->addSong($room->playlistId, '12345');

        $room->sync();

        $this->assertCount(2, $room->songs);
    }

    /** @test */
    public function adding_same_song_another_room_doesnt_duplicate_song()
    {
        /** @var Room $room */
        $room = factory(Room::class)->create();

        $room->addSong(ExternalSongFaker::withId('60SJRvzXJnVeVfS4RiH14u'));

        /** @var Room $room */
        $room = factory(Room::class)->create();

        $room->addSong(ExternalSongFaker::withId('60SJRvzXJnVeVfS4RiH14u'));

        $this->assertCount(1, Song::where('external_id', '60SJRvzXJnVeVfS4RiH14u')->get());
    }

    /** @test */
    public function removes_song_from_room()
    {
        /** @var Room $room */
        $room = factory(Room::class)->create();

        $room->addSong(ExternalSongFaker::withId('60SJRvzXJnVeVfS4RiH14u'));

        $room->removeSong('60SJRvzXJnVeVfS4RiH14u');

        $this->assertEquals(0, Song::where('external_id', '60SJRvzXJnVeVfS4RiH14u')->count());
    }

    /** @test */
    public function room_code_is_stored_when_room_is_created()
    {
        /** @var Room $room */
        $room = factory(Room::class)->create([
            'code' => null
        ]);

        $room->save();

        $this->assertNotNull($room->code);
    }
}
