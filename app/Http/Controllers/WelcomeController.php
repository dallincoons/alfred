<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;

class WelcomeController
{
    public function show(Request $request)
    {
        if (\Cookie::has('room_code')) {
            $room = Room::where('code', \Cookie::get('room_code'))->first();

            if ($room) {
                return redirect('/rooms/' . $room->getKey());
            }
        }

        return view('welcome');
    }
}
