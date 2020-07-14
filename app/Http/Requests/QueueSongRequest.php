<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QueueSongRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'song_uri' => 'required|string'
        ];
    }
}
