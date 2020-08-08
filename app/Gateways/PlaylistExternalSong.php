<?php

namespace App\Gateways;

use Illuminate\Contracts\Support\Arrayable;

class PlaylistExternalSong extends ExternalSong implements Arrayable
{
    protected $isAdded;

    public function __construct($rawSong, array $playListIds = [], string $addedBy = null)
    {
        parent::__construct($rawSong);

        $isAdded = in_array($this->getId(), $playListIds);

        data_set($this->rawSong, 'isAdded', $isAdded);
    }

    public function setAddedBy(string $addedBy)
    {
        data_set($this->rawSong, 'addedBy', $addedBy);
    }

    public function toArray()
    {
        return json_decode(json_encode($this->rawSong), true);
    }
}
