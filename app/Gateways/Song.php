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

    public function raw()
    {
        return data_get($this->song, 'item');
    }

    public function name()
    {
        return data_get($this->song, 'item.name');
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

    public function albumImages()
    {
        return data_get($this->song, 'item.album.images');
    }

    public function toArray()
    {
        return [
            'name' => $this->name(),
            'album_images' => $this->albumImages(),
        ];
    }
}
