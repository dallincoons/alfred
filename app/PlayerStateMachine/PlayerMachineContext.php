<?php

namespace App\PlayerStateMachine;

class PlayerMachineContext
{
    /**
     * @var PlayerMachineState
     */
    private $state;

    public function state(): PlayerMachineState
    {
        return $this->state;
    }

    public function setState(PlayerMachineState $state)
    {
        $this->state = $state;
    }
}
