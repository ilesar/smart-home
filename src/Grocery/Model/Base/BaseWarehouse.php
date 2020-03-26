<?php


namespace App\Grocery\Model\Base;


use App\Grocery\Service\WebInventoryService;
use Doctrine\ORM\EntityManagerInterface;

class BaseWarehouse
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var WebInventoryService
     */
    private $inventoryService;

    public function __construct(EntityManagerInterface $entityManager, WebInventoryService $inventoryService)
    {

        $this->entityManager = $entityManager;
        $this->inventoryService = $inventoryService;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return WebInventoryService
     */
    public function getInventoryService(): WebInventoryService
    {
        return $this->inventoryService;
    }

    /**
     * @param WebInventoryService $inventoryService
     */
    public function setInventoryService(WebInventoryService $inventoryService): void
    {
        $this->inventoryService = $inventoryService;
    }
}
