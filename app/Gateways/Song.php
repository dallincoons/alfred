<?php

namespace App\Gateways;

class Song
{
    /**
     * @var \stdClass|array
     */
    private $song;

    public function __construct($song)
    {
        $this->song = $song;
    }

    public function uri()
    {
        return data_get($this->song, 'item.uri');
    }

    public function id()
    {
        return data_get($this->song, 'item.id');
    }
}
