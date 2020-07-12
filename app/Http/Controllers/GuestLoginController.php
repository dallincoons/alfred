<?php

namespace App\Http\Controllers;

use App\CodeGenerator;
use App\Room;
use App\User;
use Illuminate\Http\Request;

class GuestLoginController extends Controller
{
    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    public function __construct(CodeGenerator $codeGenerator)
    {
        $this->codeGenerator = $codeGenerator;
    }

    public function show(Request $request)
    {
        $room = Room::where('code', $request->room)->first();

        if(!$room) {
            throw new \Exception('Invalid room code');
        }

        \Auth::login($room->user, true);

        \Session::put('guest_name', $request->guest_user_name ?? 'Guest');

        return redirect('/rooms/' . $room->getKey());
    }
}
