<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController
{
    public function show(Request $request)
    {
        return view('welcome');
    }
}
