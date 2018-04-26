<?php

use App\CodeGenerator;
use App\Gateways\SpotifyGatewayInterface;
use App\Room;
use Illuminate\Support\Facades\Session;
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
        $this->post('/rooms', ['room_name' => 'test123']);

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

        $room->addSong('60SJRvzXJnVeVfS4RiH14u');

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
}
