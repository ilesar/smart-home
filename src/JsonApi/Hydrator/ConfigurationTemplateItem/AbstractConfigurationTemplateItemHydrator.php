<?php

namespace App\JsonApi\Hydrator\ConfigurationTemplateItem;

use App\Entity\ConfigurationTemplateItem;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Paknahad\JsonApiBundle\Exception\InvalidRelationshipValueException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract ConfigurationTemplateItem Hydrator.
 */
abstract class AbstractConfigurationTemplateItemHydrator extends AbstractHydrator
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
        return ['configuration_template_items'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($configurationTemplateItem): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(ConfigurationTemplateItem::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($configurationTemplateItem, string $id): void
    {
        if ($id && (string) $configurationTemplateItem->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body must be the same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($configurationTemplateItem): array
    {
        return [
            'configurationItem' => function (ConfigurationTemplateItem $configurationTemplateItem, ToOneRelationship $configurationItem, $data, $relationshipName) {
                $this->validateRelationType($configurationItem, ['configuration_items']);


                $association = null;
                $identifier = $configurationItem->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\ConfigurationItem')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $configurationTemplateItem->setConfigurationItem($association);
            },
            'template' => function (ConfigurationTemplateItem $configurationTemplateItem, ToOneRelationship $template, $data, $relationshipName) {
                $this->validateRelationType($template, ['configuration_templates']);


                $association = null;
                $identifier = $template->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\ConfigurationTemplate')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $configurationTemplateItem->setTemplate($association);
            },
        ];
    }
}
