<?php

namespace Tests\Unit\Gateways;

use App\Gateways\ExternalSong;
use Tests\TestCase;

class ExternalSongTest extends TestCase
{
    /** @test */
    public function it_gets_external_attributes()
    {
        $song = new ExternalSong([
            'id' => '1234',
            'album' => [
                'artists' => [
                    0 => [
                        'name' => 'Ties That Bind'
                    ]
                ]
            ]
        ]);

        $this->assertEquals('1234', $song->getId());
        $this->assertEquals('Ties That Bind', $song->getTitle());
    }
}
