<?php

namespace App\PlayerStateMachine;

use App\Events\SongQueueStarted;
use App\Events\SongQueueUpdated;
use App\Gateways\SpotifyGatewayInterface;
use App\Song;
use App\SongQueue;
use Illuminate\Support\Facades\Session;

class IdleState implements PlayerMachineState
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
        $sessionName = $playerMachine->room()->queueSessionName();

        if(empty(Session::get($sessionName))) {
            Session::put($sessionName, $songs);
        }

        shuffle($songs);

        $currentSong = array_shift($songs);

        Session::put($sessionName, $songs);

        SongQueueStarted::dispatch(Song::where('external_id', $currentSong)->first());
        SongQueueUpdated::dispatch( $songs );

        $playerMachine->context()->setState($playingState = app(PlayingState::class));

        $this->gateway->changeDevice($playerMachine->deviceId());
        $this->gateway->shuffle(['state' => true, 'device_id' => $playerMachine->deviceId()]);
        return $this->gateway->startSong($playerMachine->deviceId(), 'spotify:track:' . $currentSong);
    }

    /**
     * @param PlayerMachine $playerMachine
     */
    public function pause(PlayerMachine $playerMachine)
    {
        //
    }

    /**
     * @param PlayerMachine $playerMachine
     */
    public function next(PlayerMachine $playerMachine)
    {
        $songs = $playerMachine->room()->songs();

        if (!$songs->count()) {
            throw new \Exception('There are no songs in the playlist');
        }

        $currentSong = SongQueue::next($playerMachine->playlistId());

        if(!$currentSong) {
            $songs = $songs->pluck('external_id')->all();
            $currentSong = array_shift($songs);
            \Session::put($playerMachine->room()->queueSessionName(), $songs);
        }

        SongQueueStarted::dispatch(Song::where('external_id', $currentSong)->first());
        SongQueueUpdated::dispatch( \Session::get($playerMachine->room()->queueSessionName()) );

        $playerMachine->context()->setState($playingState = app(PlayingState::class));

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
