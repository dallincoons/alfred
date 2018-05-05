<?php

namespace App\PlayerStateMachine;

class PausedState implements PlayerMachineState
{
    public function play(PlayerMachine $playerMachine, array $songs): bool
    {
        // TODO: Implement play() method.
    }

    public function pause(PlayerMachineContext $context)
    {
        // TODO: Implement pause() method.
    }

    public function next(PlayerMachine $playerMachine)
    {

    }
}
