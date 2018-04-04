<?php

namespace App\Gateways;

interface CrawlerInterface
{
    public function get(string $url);
    public function post(string $url, array $requestParams);
}
