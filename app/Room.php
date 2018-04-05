<?php

namespace App;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name', 'playlistId'
    ];

    public function share()
    {
        $hashids = new Hashids('', 5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        return $hashids->encode($this->getKey());
    }

    public function join(string $roomId)
    {
        $hashids = new Hashids('', 5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');

        return array_get($hashids->decode($roomId), 0) == $this->getKey();
    }
}
