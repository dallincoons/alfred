<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/connect', 'SpotifyController@connect');
Route::get('/spotify-callback', 'SpotifyController@callback');

Route::resource('rooms', 'RoomController');

Route::get('/logout', function() {
    \Auth::logout();

    return redirect('/');
});
