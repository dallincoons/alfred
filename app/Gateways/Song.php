<?php

namespace App\Gateways;

class Song
{
    /**
     * @var \stdClass|array
     */
    private $song;

    private $name;

    public function __construct($song)
    {
        $this->song = $song;
    }

    public function setContributorName(string $name)
    {
        $this->name = $name;
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

    public function artist()
    {
        return data_get($this->song, 'item.artists.0.name');
    }

    public function toArray()
    {
        return [
            'name' => $this->name(),
            'artist' => $this->artist(),
            'added_by' => $this->name,
            'album_images' => $this->albumImages(),
            'id' => $this->id(),
        ];
    }
}
