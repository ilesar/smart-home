<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\ConfigurationTemplate;
use App\Repository\ConfigurationTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;

class ActiveConfigurationEventListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function preUpdate(ConfigurationTemplate $template): void
    {
        if (false === $this->checkIfActiveValueHasChanged($template)) {
            return;
        }

        $this->deactivateOtherTemplates($template);
    }

    private function checkIfActiveValueHasChanged(ConfigurationTemplate $template): bool
    {
        $changes = $this->getChangesFromEntity($template);

        return !(null === $changes || $changes[0] === $changes[1]);
    }

    private function deactivateOtherTemplates(ConfigurationTemplate $template)
    {
        /** @var ConfigurationTemplateRepository $repository */
        $repository = $this->entityManager->getRepository(ConfigurationTemplate::class);

        $test = $repository->createQueryBuilder('t')
            ->update()
            ->set('t.isActive', '0')
            ->where('t.isActive = true')
            ->andWhere('t.configuration = :val')
            ->setParameter('val', $template->getConfiguration())
            ->getQuery()
            ->execute();
    }

    private function getChangesFromEntity(ConfigurationTemplate $template)
    {
        if (!$template instanceof ConfigurationTemplate) {
            return false;
        }

        $changeSet = $this->entityManager->getUnitOfWork()->getEntityChangeSet($template);
        return isset($changeSet['isActive']) ? $changeSet['isActive'] : null;
    }
}
