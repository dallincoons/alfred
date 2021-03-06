<?php

Route::get('/', 'WelcomeController@show');

Route::get('/not-premium', function() {
    return view('not-premium');
});

Route::get('/connect', 'SpotifyController@connect');
Route::get('/spotify-callback', 'SpotifyController@callback');
Route::get('spotify/songs', 'SpotifyController@search');
Route::get('spotify/currently-playing', 'SpotifyController@currentlyPlayingSong');
Route::post('spotify/queue-song', 'SpotifyController@addSongToQueue');

Route::resource('rooms', 'RoomController');
Route::post('room/{room}/song', 'RoomSongController@store');
Route::delete('room/{room}/{song}', 'RoomSongController@delete');

Route::put('room/{room}/play', 'RoomPlaylistController@play');
Route::put('room/{room}/pause', 'RoomPlaylistController@pause');
Route::put('room/{room}/resume', 'RoomPlaylistController@resume');
Route::put('room/{room}/next', 'RoomPlaylistController@next');
Route::put('room/{room}/previous', 'RoomPlaylistController@previous');
Route::post('room/{room}/device', 'RoomPlaylistController@device');
Route::patch('room/{room}/song/{song}/play', 'RoomPlaylistController@playSong');

Route::post('room/join', 'GuestLoginController@show');

Route::get('/logout', function() {
    \Auth::logout();

    \Cookie::queue(\Cookie::forget('room_code'));

    return redirect('/');
});
