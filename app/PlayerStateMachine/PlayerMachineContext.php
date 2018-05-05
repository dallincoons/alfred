<?php

namespace App\PlayerStateMachine;

use Illuminate\Support\Facades\Session;

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
        Session::put('player', get_class($state));

        $this->state = $state;
    }
}
