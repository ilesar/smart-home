<?php

namespace App\Grocery\Service;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;

class WebInventoryService
{
    private $client;

    public function open(): void
    {
        $this->client = Client::createChromeClient(null, [
            '--disable-gpu',
            '--headless',
            '--window-size=1920x1080',
            '--no-sandbox',
            '--disable-popup-blocking',
            '--disable-application-cache',
            '--disable-web-security',
            '--start-maximized',
            '--ignore-certificate-errors',
//            '--proxy-server=socks://127.0.0.1:9050'
        ]);
    }

    public function close(): void
    {
        $this->client->close();
    }

    /**
     * @return mixed
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}
