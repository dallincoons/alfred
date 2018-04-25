<?php

namespace App;

class GuestUser extends User
{
    protected $table = 'users';

    public function user()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function getAccessTokenAttribute()
    {
        return $this->user->access_token;
    }
}
