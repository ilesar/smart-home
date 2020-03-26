<?php

namespace App\JsonApi\Hydrator\ConfigurationItem;

use App\Entity\ConfigurationItem;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract ConfigurationItem Hydrator.
 */
abstract class AbstractConfigurationItemHydrator extends AbstractHydrator
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
        return ['configuration_items'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($configurationItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(ConfigurationItem::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($configurationItem, string $id): void
    {
        if ($id && (string) $configurationItem->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body must be the same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($configurationItem): array
    {
        return [
            'configuration' => function (ConfigurationItem $configurationItem, ToOneRelationship $configuration, $data, $relationshipName) {
                $this->validateRelationType($configuration, ['configurations']);

                $association = null;
                $identifier = $configuration->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\Configuration')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $configurationItem->setConfiguration($association);
            },
        ];
    }
}
