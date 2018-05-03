<?php

namespace Tests\Fakes;

use App\Gateways\ExternalSong;

class ExternalSongFaker
{
    public static function withId(string $id)
    {
        return new ExternalSong(self::validParams([
            'id' => $id
        ]));
    }

    public static function any()
    {
        return new ExternalSong(self::validParams());
    }

    public static function validParams(array $overrides = []): array
    {
        return array_merge([
            'id' => '1234',
            'name' => 'Ties That Bind',
            'album' => [
                'artists' => [
                    0 => [
                        'name' => 'Bummer Deal'
                    ]
                ]
            ]
        ], $overrides);
    }
}
