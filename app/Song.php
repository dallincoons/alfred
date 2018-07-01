<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'external_id', 'artist_title', 'title',
        'duration', 'big_image', 'added_by'
    ];
}
