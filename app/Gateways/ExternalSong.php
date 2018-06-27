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

    /**
     * @return string
     */
    public function getId()
    {
        return array_get($this->rawSong, 'id');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return array_get($this->rawSong, 'name');
    }

    /**
     * @return string
     */
    public function getArtistTitle()
    {
        return array_get($this->rawSong, 'album.artists.0.name');
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return array_get($this->rawSong, 'duration_ms');
    }

    /**
     * @return int
     */
    public function getBigImage()
    {
        return array_get($this->rawSong, 'album.images.0.url');
    }
}
