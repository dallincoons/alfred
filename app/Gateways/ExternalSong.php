<?php

namespace App\Gateways;

class ExternalSong
{
    /**
     * @var array $rawSong
     */
    private $rawSong;

    public function __construct(array $rawSong)
    {
        $this->rawSong = $rawSong;
    }

    public function getId()
    {
        return array_get($this->rawSong, 'id');
    }

    public function getTitle()
    {
        return array_get($this->rawSong, 'name');
    }

    public function getArtistTitle()
    {
        return array_get($this->rawSong, 'album.artists.0.name');
    }
}
