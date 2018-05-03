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
            'name' => 'Ties That Bind',
            'album' => [
                'artists' => [
                    0 => [
                        'name' => 'Shellac'
                    ]
                ]
            ]
        ]);

        $this->assertEquals('1234', $song->getId());
        $this->assertEquals('Ties That Bind', $song->getTitle());
        $this->assertEquals('Shellac', $song->getArtistTitle());
    }
}
