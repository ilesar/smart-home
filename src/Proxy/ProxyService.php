<?php

namespace App\Proxy;

use App\Entity\ProxyServer;
use App\Enum\ProxyDirective;
use App\Proxy\Interfaces\ProxySourceInterface;
use App\Repository\ProxyServerRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ProxyService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ProxySourceInterface[]
     */
    private $proxySources;

    /**
     * @var ProxyServerRepository
     */
    private $proxyServerRepository;

    public function __construct(EntityManagerInterface $entityManager, $proxySources)
    {
        $this->entityManager = $entityManager;
        $this->proxySources = $proxySources;
        $this->proxyServerRepository = $this->entityManager->getRepository(ProxyServer::class);
    }

    public function getProxy(string $proxyDirective = ProxyDirective::LEAST_ATTEMPTS): ProxyServer
    {
        switch ($proxyDirective) {
            case ProxyDirective::GREEDY:
                return $this->proxyServerRepository->findBestKnownProxy();
            case ProxyDirective::LEAST_ATTEMPTS:
                return $this->proxyServerRepository->findProxyWithLeastAttempts();
            default:
                throw new Exception(sprintf('Unknown proxy directive (%s)', $proxyDirective));
        }
    }

    public function blacklistProxy(ProxyServer $proxyServer): void
    {
        $proxyServer->setIsBlacklisted(true);
        $proxyServer->setBlacklistedAt(new DateTime());

        $this->entityManager->flush();
    }

    public function refreshProxyDatabase(): void
    {
        foreach ($this->proxySources as $proxySource) {
            $proxyList = $proxySource->getProxyList();
            $this->saveNewProxies($proxyList);
        }
    }

    private function saveNewProxies($proxyList): void
    {
        foreach ($proxyList as $proxyServer) {
            $knownProxyServer = $this->proxyServerRepository->findOneBy([
                'host' => $proxyServer->getHost(),
                'port' => $proxyServer->getPort(),
            ]);

            if (null !== $knownProxyServer) {
                continue;
            }

            $this->entityManager->persist($proxyServer);
        }

        $this->entityManager->flush();
    }
}
