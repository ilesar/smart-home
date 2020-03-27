<?php

namespace App\Proxy\Model;

use App\Proxy\Interfaces\ProxySourceInterface;

class ClarketmProxySource extends BaseProxySource implements ProxySourceInterface
{

    public function getProxyList()
    {
        return [];
    }
}
