<?php

namespace Tests\Feature;

use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class SpotifyControllerTest extends TestCase
{
    /** @test */
    public function it_redirects_non_premium_users()
    {
        $this->withoutExceptionHandling();

        Socialite::shouldReceive('driver->stateless->user')
            ->andReturn((object) [
                'user' => [
                    'product' => 'open'
                ]
            ]);

        $response = $this->get('/spotify-callback');

        $response->assertRedirect('/not-premium');
    }
}
