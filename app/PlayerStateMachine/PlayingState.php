<?php

namespace App\PlayerStateMachine;

use App\Events\SongQueueStarted;
use App\Events\SongQueueUpdated;
use App\Gateways\SpotifyGatewayInterface;
use App\Song;
use App\SongQueue;
use Illuminate\Support\Facades\Session;

class PlayingState implements PlayerMachineState
{
    /**
     * @var SpotifyGatewayInterface
     */
    private $gateway;

    public function __construct(SpotifyGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function play(PlayerMachine $playerMachine, array $songs): bool
    {
        return false;
    }

    public function playSong(PlayerMachine $playerMachine, string $song): bool
    {
        return $this->gateway->startSong($playerMachine->deviceId(), 'spotify:track:' . $song);
    }

    public function pause(PlayerMachine $playerMachine)
    {
        $this->gateway->pause($playerMachine->deviceId());

        $playerMachine->context()->setState($pausedState = app(PausedState::class));
    }

    public function next(PlayerMachine $playerMachine)
    {
        $currentSong = SongQueue::next($playerMachine->playlistId());

        if(!$currentSong) {
            $songs = $playerMachine->room()->songs()->pluck('external_id')->all();
            $currentSong = array_shift($songs);
            Session::put($playerMachine->room()->queueSessionName(), $songs);
        }

        SongQueueStarted::dispatch(Song::where('external_id', $currentSong)->first());
        SongQueueUpdated::dispatch( \Session::get($playerMachine->room()->queueSessionName()) );

        return $this->gateway->startSong($playerMachine->deviceId(), 'spotify:track:' . $currentSong);
    }

    /**
     * @param PlayerMachine $playerMachine
     */
    public function resume(PlayerMachine $playerMachine)
    {
        // TODO: Implement resume() method.
    }
}
