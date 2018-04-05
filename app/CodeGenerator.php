<?php

namespace App;

use Hashids\Hashids;

class CodeGenerator
{
    /**
     * @var Hashids
     */
    private $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids(env('HASHID_SALT', ''), 5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }

    public function encode($value)
    {
        return $this->hashids->encode($value);
    }

    public function decode($value)
    {
        return $this->hashids->decode($value);
    }
}
