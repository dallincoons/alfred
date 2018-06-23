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
    public function guest_can_join_room_using_code()
    {
        $room = factory(Room::class)->create();

        $response = $this->post('/room/join', ['room' => $room->share()]);

        $response->assertRedirect('/rooms/' . $room->getKey());
        $this->assertTrue(\Auth::user()->hasParent());
    }

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

        $response = $this->post('/room/join', ['room' => $room->share()]);

        $response->assertRedirect('/rooms/' . $room->getKey());
        $this->assertEquals(\Auth::user(), ($room->user->guestUser));
    }

    /** @test */
    public function room_can_be_joined_with_lower_case_correct_code()
    {
        $room = factory(Room::class)->create();

        auth()->logout();

        $response = $this->post('/room/join', ['room' => strtolower($room->share())]);

        $response->assertRedirect('/rooms/' . $room->getKey());
        $this->assertEquals(\Auth::user(), ($room->user->guestUser));
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
        $roomId = $room->share();

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
    public function play_next_song()
    {
        /** @var Room $room */
        $room = factory(Room::class)->create();

        $room->addSong(ExternalSongFaker::withId('60SJRvzXJnVeVfS4RiH14u'));
        $room->addSong(ExternalSongFaker::withId('6SWTXSLOkxrFqJc6WPM0bu'));

        $this->assertCount(2, Session::get($room->queueSessionName()));

        $room->play();

        $this->assertCount(1, Session::get($room->queueSessionName()));

        $room->next();

        $this->assertCount(0, Session::get($room->queueSessionName()));

        $room->next();

        $this->assertCount(1, Session::get($room->queueSessionName()));
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
    public function it_gets_rooms_queue()
    {
        $room = factory(Room::class)->create();

        $room->addSong(ExternalSongFaker::any());
        $room->addSong(ExternalSongFaker::any());

        $this->assertCount(2, $room->getQueue());
    }
}
