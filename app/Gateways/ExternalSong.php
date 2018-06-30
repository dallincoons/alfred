<?php

namespace App\Gateways;

class ExternalSong
{
    /**
     * @var array $rawSong
     */
    protected $rawSong;

    public function __construct($rawSong)
    {
        $this->rawSong = $rawSong;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return data_get($this->rawSong, 'id');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return data_get($this->rawSong, 'name');
    }

    /**
     * @return string
     */
    public function getArtistTitle()
    {
        return data_get($this->rawSong, 'album.artists.0.name');
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return data_get($this->rawSong, 'duration_ms');
    }

    /**
     * @return int
     */
    public function getBigImage()
    {
        return data_get($this->rawSong, 'album.images.0.url');
    }
}
