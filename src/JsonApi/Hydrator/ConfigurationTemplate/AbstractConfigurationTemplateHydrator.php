<?php

namespace App\JsonApi\Hydrator\ConfigurationTemplate;

use App\Entity\ConfigurationTemplate;
use Doctrine\ORM\Query\Expr;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract ConfigurationTemplate Hydrator.
 */
abstract class AbstractConfigurationTemplateHydrator extends AbstractHydrator
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
        return ['configuration_templates'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($configurationTemplate): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(ConfigurationTemplate::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($configurationTemplate, string $id): void
    {
        if ($id && (string) $configurationTemplate->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body must be the same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($configurationTemplate): array
    {
        return [
            'configuration' => function (ConfigurationTemplate $configurationTemplate, ToOneRelationship $configuration, $data, $relationshipName) {
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

                $configurationTemplate->setConfiguration($association);
            },
            'items' => function (ConfigurationTemplate $configurationTemplate, ToManyRelationship $items, $data, $relationshipName) {
                $this->validateRelationType($items, ['configuration_template_items']);

                if (count($items->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\ConfigurationTemplateItem')
                        ->createQueryBuilder('i')
                        ->where((new Expr())->in('i.id', $items->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $items->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($configurationTemplate->getItems()->count() > 0) {
                    foreach ($configurationTemplate->getItems() as $item) {
                        $configurationTemplate->removeItem($item);
                    }
                }

                foreach ($association as $item) {
                    $configurationTemplate->addItem($item);
                }
            },
        ];
    }
}
