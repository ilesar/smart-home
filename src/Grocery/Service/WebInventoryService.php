<?php

namespace App\Grocery\Service;

use Symfony\Component\Panther\Client;

class WebInventoryService
{
    private $scraper;

    public function __construct()
    {
        $this->scraper = Client::createChromeClient(null, [
            '--disable-gpu',
            '--headless',
            '--window-size=1200x1100',
            '--no-sandbox',
            '--disable-popup-blocking',
            '--disable-application-cache',
            '--disable-web-security',
            '--start-maximized',
            '--ignore-certificate-errors',
//            '--proxy-server=socks://127.0.0.1:9050'
        ], [], 'https://www.konzum.hr');
    }

    public function getPage(string $url, string $waitToAppearSelector)
    {
        $page = $this->scraper->request('GET', $url);

        if ($waitToAppearSelector) {
            $this->scraper->waitFor($waitToAppearSelector);
        }

        return $page;
    }

    public function close()
    {
        $this->scraper->close();
    }
}
