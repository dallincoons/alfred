<?php

namespace App\Http\Controllers;

use App\CodeGenerator;
use App\Repositories\GuestNameRepository;
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

        app(GuestNameRepository::class)->setUserName($request->guest_user_name);
        \Cookie::queue('room_code', $room->code, 864000);

        return redirect('/rooms/' . $room->getKey());
    }
}
