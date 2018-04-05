<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class GuestUser extends Authenticatable
{
    protected $fillable = [
        'parent_user_id'
    ];
}
