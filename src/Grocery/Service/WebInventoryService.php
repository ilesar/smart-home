<?php


namespace App\Grocery\Service;


use Symfony\Component\Panther\Client;

class WebInventoryService
{

    private $scraper;

    public function __construct()
    {
        $this->scraper = Client::createChromeClient();
    }

    public function getPage(string $url)
    {
        $this->scraper->request('GET', $url);
//        $this->scraper->waitFor('.message');
    }
}
