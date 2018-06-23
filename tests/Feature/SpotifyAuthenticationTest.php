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
                    'product' => 'premium',
                    'images' => [
                        0 => [
                            'url' => $url = 'https://scontent.xx.fbcdn.net/v/t1.0-1/p200x200/10881700_10202381986186063_7458105300395742320_n.jpg?_nc_cat=0&oh=7811658b6aef8420005271db6768b0ee&oe=5BB81B3A'
                        ]
                    ]
                ]
            ]);

        $this->get('/spotify-callback')
            ->assertRedirect('/');

        $this->assertEquals($token, \Auth::user()->access_token);
        $this->assertEquals($url, \Auth::user()->profile_image);
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
