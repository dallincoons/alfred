<?php

namespace App\Http\Controllers;

use App\CodeGenerator;
use App\Room;
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
        $room = Room::find(array_first($this->codeGenerator->decode($request->room)));

        \Auth::login($room->user->guestUser);

        return redirect('/rooms/' . $room->getKey());
    }
}
