<?php

namespace App\Repositories;

class GuestNameRepository
{
    public function setUserName($userName)
    {
        \Session::put('guest_name', $userName ?? 'Guest');
        \Cookie::queue('guest_name', $userName);
    }
}
