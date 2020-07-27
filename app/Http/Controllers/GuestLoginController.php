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
            return redirect()->back()->with('errors', ['Invalid room code'])->withInput();
        }

        \Auth::login($room->user, true);

        \Session::put('guest_name', $request->guest_user_name ?? 'Guest');
        \Cookie::queue('guest_name', $request->guest_user_name);

        return redirect('/rooms/' . $room->getKey());
    }
}
