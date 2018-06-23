<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * @param $userId
     * @param $playlistId
     * @return array
     */
    public static function getUserQueue($userId, $playlistId)
    {
        if ( !$user = \DB::table('sessions')->where('user_id', $userId)->first() ) {
            return [];
        }

        $payload = unserialize( base64_decode($user->payload));

        return array_get($payload, 'playlist:' . $playlistId, []);
    }
}
