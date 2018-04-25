<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/connect', 'SpotifyController@connect');
Route::get('/spotify-callback', 'SpotifyController@callback');
Route::get('spotify/songs', 'SpotifyController@songs');

Route::resource('rooms', 'RoomController');
Route::post('room/{room}/song/{song}', 'RoomSongController@store');

Route::put('room/{room}/device/{device}/play', 'RoomPlaylistController@play');
Route::put('room/{room}/pause', 'RoomPlaylistController@pause');
Route::put('room/{room}/resume', 'RoomPlaylistController@resume');
Route::put('room/{room}/next', 'RoomPlaylistController@next');
Route::post('room/{room}/device', 'RoomPlaylistController@device');

Route::post('room/join', 'GuestLoginController@show');

Route::get('/logout', function() {
    \Auth::logout();

    return redirect('/');
});
