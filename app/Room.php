<?php

namespace App;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name', 'playlistId'
    ];

    /**
     * @var CodeGenerator
     */
    private $codeGenerator;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->codeGenerator = app(CodeGenerator::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function share()
    {
        return $this->codeGenerator->encode($this->getKey());
    }

    public function join(string $roomId)
    {
        if (array_get($this->codeGenerator->decode($roomId), 0) == $this->getKey()) {
            \Auth::login($this->user->guestUser);
            return true;
        }

        return false;
    }
}
