<?php

use Tests\TestCase;

class SongTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function it_searches_songs()
    {
        $response = $this->get('spotify/songs?q=caroline');

        $response->assertSuccessful();
        $this->assertArrayHasKey('tracks', $response->decodeResponseJson());
    }
}
