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

    public static function any(array $overrides = [])
    {
        return new ExternalSong(self::validParams($overrides));
    }

    public static function validParams(array $overrides = []): array
    {
        return array_merge([
            'id' => str_random(10),
            'name' => 'Ties That Bind',
            'duration_ms' => 123456,
            'album' => [
                'artists' => [
                    0 => [
                        'name' => 'Bummer Deal'
                    ]
                ],
                'images' => [
                    0 => [
                        'url' => 'some_image.jpg'
                    ]
                ]
            ]
        ], $overrides);
    }
}
