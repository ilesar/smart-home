<?php

namespace App\Grocery\Service;

use App\Entity\ProxyServer;
use App\Enum\ProxyDirective;
use App\Proxy\ProxyService;
use Exception;
use Symfony\Component\Panther\Client;

class WebInventoryService
{
    private $client;
    /**
     * @var ProxyService
     */
    private $proxyService;

    /**
     * @var ProxyServer
     */
    private $proxyServer;

    private const CHROME_SETTINGS = [
        '--disable-gpu',
        '--headless',
        '--window-size=1920x1080',
        '--no-sandbox',
        '--disable-popup-blocking',
        '--disable-application-cache',
        '--disable-web-security',
        '--start-maximized',
        '--ignore-certificate-errors',
    ];

    public function __construct(ProxyService $proxyService)
    {
        $this->proxyService = $proxyService;
    }

    public function open(): void
    {
        $this->proxyServer = $this->proxyService->getProxy(ProxyDirective::LEAST_ATTEMPTS);

        if (!$this->proxyServer) {
            throw new Exception('No more proxies');
        }

        $proxyConfig = vsprintf('--proxy-server=%s:%s', [
            $this->proxyServer->getHost(),
            $this->proxyServer->getPort(),
        ]);

//        $proxyConfig = '--proxy-server=socks://127.0.0.1:9050';
//        $proxyConfig = '--proxy-server=127.0.0.1:8000';

        $config = self::CHROME_SETTINGS;
//        $config[] = $proxyConfig;

//        echo 'NEW PROXY: ' . $this->proxyServer->getHost() . ':' . $this->proxyServer->getPort() . PHP_EOL;
//        echo '$config: ' . $proxyConfig . PHP_EOL;

        if ($this->client) {
            $this->close();
        }

        $this->client = Client::createChromeClient(null, $config);
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

    public function blacklistCurrentClient()
    {
        $this->proxyService->blacklistProxy($this->proxyServer);
    }
}
