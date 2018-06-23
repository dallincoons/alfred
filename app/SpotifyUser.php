<?php

namespace App;

class SpotifyUser
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $access_token;

    /**
     * @var string
     */
    public $refresh_token;

    /**
     * @var string
     */
    public $uri;

    /**
     * @var string
     */
    public $profile_image;

    public function __construct(string $id, string $name, string $access_token, string $refresh_token, string $uri, string $profile_image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
        $this->uri = $uri;
        $this->profile_image = $profile_image;
    }
}
