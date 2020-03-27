<?php

namespace App\Proxy\Model\Base;

use App\Client\ProxySourceClient;
use App\Entity\ProxyServer;
use App\Exception\MethodNotImplementedException;
use GuzzleHttp\Client;

class BaseProxySource
{
    /**
     * @var Client
     */
    private $httpClient;

    protected const PROXY_SOURCE = null;

    public function __construct(ProxySourceClient $proxySourceClient)
    {
        $this->httpClient = $proxySourceClient;
    }

    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    public function setHttpClient(Client $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    public function getProxyList()
    {
        $rawProxyList = $this->getRawProxyList();

        return $this->parseProxyList($rawProxyList);
    }

    protected function getRawProxyList()
    {
        $response = $this->getHttpClient()->request('GET', $this->getProxyUrl());

        return $response->getBody()->getContents();
    }

    /**
     * @param string $rawProxyList
     * @throws MethodNotImplementedException
     * @return ProxyServer[]
     */
    protected function parseProxyList(string $rawProxyList): array
    {
        throw new MethodNotImplementedException('parseProxyList() method not implemented');
    }

    protected function getProxyUrl(): string
    {
        throw new MethodNotImplementedException('parseProxyList() method not implemented');
    }
}
