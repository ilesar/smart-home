<?php


namespace App\Proxy\Interfaces;


use App\Entity\ProxyServer;

interface ProxySourceInterface
{
    /** @return ProxyServer[] */
    public function getProxyList();
}
