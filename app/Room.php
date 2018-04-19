<?php

namespace App;

use App\Gateways\SpotifyGatewayInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Room extends Model
{
    protected $fillable = [
        'name', 'playlistId'
    ];

    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    /**
     * @var SpotifyGatewayInterface
     */
    private $gateway;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->codeGenerator = app(CodeGenerator::class);
        $this->gateway = app(SpotifyGatewayInterface::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function songs()
    {
        return $this->belongsToMany(Song::class);
    }

    public function share()
    {
        return $this->codeGenerator->encode($this->getKey());
    }

    public function join(string $roomId)
    {
        if (array_get($this->codeGenerator->decode($roomId), 0) == $this->getKey()) {
            \Auth::login($this->user->guestUser);
            return true;
        }

        return false;
    }

    public function addSong(string $songId)
    {
        $this->songs()->create([
            'external_id' => $songId
        ]);
        $this->gateway->addSong($this->playlistId, $songId);
    }

    public function play(string $deviceId)
    {
        if(empty(Session::get('playlist:' . $this->playlistId))) {
            Session::put('playlist:' . $this->playlistId, ($this->songs()->inRandomOrder()->pluck('external_id')->all()));
        }
        $playlist = Session::get('playlist:' . $this->playlistId);
        $song = array_pop($playlist);
        Session::put('playlist:' . $this->playlistId, $playlist);
        return $this->gateway->startSong($deviceId, 'spotify:track:' . $song);
    }

    public function pause(string $deviceId)
    {
        return $this->gateway->pause($deviceId);
    }

    public function resume(string $deviceId)
    {
        return $this->gateway->resumeSong($deviceId);
    }
}
