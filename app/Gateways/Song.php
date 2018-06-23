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

    /**
     * @return string
     */
    public function uri()
    {
        return data_get($this->song, 'item.uri');
    }

    /**
     * @return string
     */
    public function id()
    {
        return data_get($this->song, 'item.id');
    }
}
