<?php

namespace Tests\Feature;

use App\Room;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class SpotifyAuthenticationTest extends TestCase
{
    /** @test */
    public function it_logs_in_user_and_redirects()
    {
        $this->withoutExceptionHandling();

        \Auth::logout();

        Socialite::shouldReceive('driver->stateless->user')
            ->andReturn((object) [
                'id' => 1,
                'name' => 'Johnny',
                'token' => $token = str_random(15),
                'refreshToken' => str_random(15),
                'user' => [
                    'uri' => str_random(10),
                    'product' => 'premium'
                ]
            ]);

        $this->get('/spotify-callback')
            ->assertRedirect('/');

        $this->assertEquals($token, \Auth::user()->access_token);
    }

    /** @test */
    public function it_logs_in_user_and_redirects_to_room_if_session_data_exists()
    {
        $this->withoutExceptionHandling();

        \Auth::logout();

        \Session::put('create-room', [
            'name' => 'test123'
        ]);

        Socialite::shouldReceive('driver->stateless->user')
            ->andReturn((object) [
                'id' => 1,
                'name' => 'Johnny',
                'token' => $token = str_random(15),
                'refreshToken' => str_random(15),
                'user' => [
                    'uri' => str_random(10),
                    'product' => 'premium'
                ]
            ]);

        $response = $this->get('/spotify-callback');

        $room = Room::where('name', 'test123')->first();

        $response->assertRedirect('/rooms/' . $room->getKey());

        $this->assertInstanceOf(Room::class, $room);

        $this->assertEquals($token, \Auth::user()->access_token);
    }
}
