<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/connect', 'SpotifyController@connect');
Route::get('/spotify-callback', 'SpotifyController@callback');
Route::get('spotify/songs', 'SpotifyController@songs');

Route::resource('rooms', 'RoomController');
Route::post('room/{room}/song/{song}', 'RoomSongController@store');

Route::get('/logout', function() {
    \Auth::logout();

    return redirect('/');
});
