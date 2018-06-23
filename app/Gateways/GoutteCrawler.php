<?php

namespace App\Gateways;

class GoutteCrawler implements CrawlerInterface
{
    /**
     * @param string $url
     * @param array $headers
     */
    public function get(string $url, array $headers = [])
    {
        foreach($headers as $key => $value) {
            \Goutte::setHeader($key, $value);
        }

        \Goutte::request('GET', $url);

        return \Goutte::getResponse();
    }

    /**
     * @param string $url
     * @param array $requestParams
     * @param array $headers
     */
    public function post(string $url, array $requestParams = [], array $headers = [])
    {
        foreach($headers as $key => $value) {
            \Goutte::setHeader($key, $value);
        }

        \Goutte::request('POST', $url, $requestParams);

        return \Goutte::getResponse();
    }
}
