<?php

namespace App;

use App\Gateways\SpotifyGatewayInterface;
use Hashids\Hashids;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'spotify_id', 'access_token', 'refresh_token', 'uri'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function guestUser()
    {
        return $this->hasOne(GuestUser::class, 'parent_user_id');
    }

    /**
     * @param string $name
     * @return Room
     */
    public function createRoom(string $name)
    {
        $playlistId = app(SpotifyGatewayInterface::class)->createPlaylist($name, $this->spotify_id);

        return $this->rooms()->create([
            'name' => $name,
            'playlistId' => $playlistId
        ]);
    }
}
