<?php

namespace App\JsonApi\Hydrator\Configuration;

use App\Entity\Configuration;
use Doctrine\ORM\Query\Expr;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract Configuration Hydrator.
 */
abstract class AbstractConfigurationHydrator extends AbstractHydrator
{
    use ValidatorTrait;

    /**
     * {@inheritdoc}
     */
    protected function validateClientGeneratedId(
        string $clientGeneratedId,
        JsonApiRequestInterface $request,
        ExceptionFactoryInterface $exceptionFactory
    ): void {
        if (!empty($clientGeneratedId)) {
            throw $exceptionFactory->createClientGeneratedIdNotSupportedException($request, $clientGeneratedId);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function generateId(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    protected function getAcceptedTypes(): array
    {
        return ['configurations'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($configuration): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(Configuration::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($configuration, string $id): void
    {
        if ($id && (string) $configuration->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body must be the same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($configuration): array
    {
        return [
            'items' => function (Configuration $configuration, ToManyRelationship $items, $data, $relationshipName) {
                $this->validateRelationType($items, ['configuration_items']);

                if (count($items->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\ConfigurationItem')
                        ->createQueryBuilder('i')
                        ->where((new Expr())->in('i.id', $items->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $items->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($configuration->getItems()->count() > 0) {
                    foreach ($configuration->getItems() as $item) {
                        $configuration->removeItem($item);
                    }
                }

                foreach ($association as $item) {
                    $configuration->addItem($item);
                }
            },
            'templates' => function (Configuration $configuration, ToManyRelationship $templates, $data, $relationshipName) {
                $this->validateRelationType($templates, ['configuration_templates']);

                if (count($templates->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\ConfigurationTemplate')
                        ->createQueryBuilder('t')
                        ->where((new Expr())->in('t.id', $templates->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $templates->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($configuration->getTemplates()->count() > 0) {
                    foreach ($configuration->getTemplates() as $template) {
                        $configuration->removeTemplate($template);
                    }
                }

                foreach ($association as $template) {
                    $configuration->addTemplate($template);
                }
            },
            'device' => function (Configuration $configuration, ToOneRelationship $device, $data, $relationshipName) {
                $this->validateRelationType($device, ['devices']);

                $association = null;
                $identifier = $device->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\Device')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $configuration->setDevice($association);
            },
        ];
    }
}
