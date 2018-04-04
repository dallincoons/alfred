<?php

namespace App\Gateways;

use App\SpotifyUser;
use App\User;

class Spotify
{
    public static function login(SpotifyUser $spotifyUser)
    {
        $user = User::firstOrNew(['spotify_id' => $spotifyUser->id]);

        if (!$user->exists) {
            $user->fill([
                'name' => $spotifyUser->name,
                'spotify_id' => $spotifyUser->id
            ])->save();
        };

        auth()->login($user);

        return $user;
    }
}
