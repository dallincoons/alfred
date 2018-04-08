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

    /** @test */
    public function add_song_to_rooms_playlist()
    {
        $room = $this->user->createRoom('Ramones');

        $response = $this->post('room/' . $room->getKey() . '/song/0n2AFRt8NnS3ATlXhnoO49');

        $response->assertSuccessful();
        $this->assertContains('0n2AFRt8NnS3ATlXhnoO49', $room->getSongs());
    }
}
