<?php

namespace App;

class Spotify
{
    public static function login(SpotifyUser $spotifyUser)
    {
        $user = User::firstOrNew(['spotify_id' => $spotifyUser->id]);

        if (!$user->exists) {
            $user->fill([
                'access_token' => $spotifyUser->access_token,
                'refresh_token' => $spotifyUser->refresh_token,
                'name' => $spotifyUser->name,
                'spotify_id' => $spotifyUser->id,
                'uri' => $spotifyUser->uri,
            ])->save();

            $guest = $user->replicate();
            $guest->parent_id = $user->getKey();
            $guest->save();
        } else {
            $user->fill([
                'access_token' => $spotifyUser->access_token,
                'refresh_token' => $spotifyUser->refresh_token,
            ])->save();

            $user->guestUser->fill([
                'access_token' => $spotifyUser->access_token,
                'refresh_token' => $spotifyUser->refresh_token,
            ])->save();
        }

        auth()->login($user);

        return $user;
    }
}
