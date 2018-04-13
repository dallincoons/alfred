<?php

namespace Tests\Traits;

trait UsesVcr
{
    /** @before */
    public function turnOn()
    {
        // After turning on the VCR will intercept all requests
        \VCR\VCR::turnOn();
    }

    public function setCassettePath(string $path)
    {
        \VCR\VCR::configure()->setCassettePath(base_path($path));
    }

    public function insertCassette(string $path)
    {
        // Record requests and responses in cassette file 'example'
        \VCR\VCR::insertCassette($path);
    }

    /** @after */
    public function turnOff()
    {
        // To stop recording requests, eject the cassette
        \VCR\VCR::eject();

        // Turn off VCR to stop intercepting requests
        \VCR\VCR::turnOff();
    }
}
