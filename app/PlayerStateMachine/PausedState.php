<?php

namespace App\PlayerStateMachine;

use App\Events\SongQueueStarted;
use App\Gateways\SpotifyGatewayInterface;
use App\Song;
use App\SongQueue;

class PausedState implements PlayerMachineState
{
    /**
     * @var SpotifyGatewayInterface
     */
    private $gateway;

    public function __construct(SpotifyGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @param PlayerMachine $playerMachine
     * @param array $songs
     * @return bool
     */
    public function play(PlayerMachine $playerMachine, array $songs): bool
    {
        // TODO: Implement play() method.
    }

    public function playSong(PlayerMachine $playerMachine, string $song): bool
    {
        SongQueueStarted::dispatch(Song::where('external_id', $song)->first());
        return $this->gateway->startSong($playerMachine->deviceId(), 'spotify:track:' . $song);
    }

    /**
     * @param PlayerMachine $playerMachine
     */
    public function pause(PlayerMachine $playerMachine)
    {
        // TODO: Implement pause() method.
    }

    /**
     * @param PlayerMachine $playerMachine
     */
    public function next(PlayerMachine $playerMachine)
    {
        $currentSong = SongQueue::next($playerMachine->playlistId());

        if(!$currentSong) {
            $songs = $playerMachine->room()->songs()->pluck('external_id')->all();
            $currentSong = array_shift($songs);
            \Session::put($playerMachine->room()->queueSessionName(), $songs);
        }

        SongQueueStarted::dispatch(Song::where('external_id', $currentSong)->first());

        $playerMachine->context()->setState(app(PlayingState::class));

        return $this->gateway->startSong($playerMachine->deviceId(), 'spotify:track:' . $currentSong);
    }

    /**
     * @param PlayerMachine $playerMachine
     */
    public function resume(PlayerMachine $playerMachine)
    {
        $this->gateway->resumeSong($playerMachine->deviceId());

        $playerMachine->context()->setState(app(PlayingState::class));
    }
}
