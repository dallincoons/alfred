<?php

namespace App\PlayerStateMachine;

use App\Gateways\SpotifyGatewayInterface;

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
