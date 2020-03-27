<?php

namespace App\Proxy\Model;

use App\Entity\ProxyServer;
use App\Exception\MethodNotImplementedException;
use App\Proxy\Interfaces\ProxySourceInterface;
use App\Proxy\Model\Base\BaseProxySource;

class UsProxyProxySource extends BaseProxySource implements ProxySourceInterface
{
    protected const PROXY_SOURCE = 'https://www.us-proxy.org/';


    protected function getProxyUrl(): string
    {
        return self::PROXY_SOURCE;
    }

    protected function parseProxyList(string $rawProxyList): array
    {
        $newProxyList = [];
        preg_match_all('/(\b(?:\d{1,3}\.){3}\d{1,3}\b)<\/td><td>(\d+)/', $rawProxyList, $matches, PREG_OFFSET_CAPTURE);

        $proxyCount = count($matches[0]);

        for ($i = 0; $i < $proxyCount; ++$i) {
            $proxyServer = new ProxyServer();
            $proxyServer->setHost($matches[1][$i][0]);
            $proxyServer->setPort($matches[2][$i][0]);

            $newProxyList[] = $proxyServer;
        }

        return $newProxyList;
    }
}
