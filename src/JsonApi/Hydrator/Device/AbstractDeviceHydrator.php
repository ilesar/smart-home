<?php

namespace App\JsonApi\Hydrator\Device;

use App\Entity\Device;
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
 * Abstract Device Hydrator.
 */
abstract class AbstractDeviceHydrator extends AbstractHydrator
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
        return ['devices'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($device): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(Device::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($device, string $id): void
    {
        if ($id && (string) $device->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body must be the same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($device): array
    {
        return [
            'room' => function (Device $device, ToOneRelationship $room, $data, $relationshipName) {
                $this->validateRelationType($room, ['rooms']);

                $association = null;
                $identifier = $room->getResourceIdentifier();
                if ($identifier) {
                    $association = $this->objectManager->getRepository('App\Entity\Room')
                        ->find($identifier->getId());

                    if (is_null($association)) {
                        throw new InvalidRelationshipValueException($relationshipName, [$identifier->getId()]);
                    }
                }

                $device->setRoom($association);
            },
            'measurements' => function (Device $device, ToManyRelationship $measurements, $data, $relationshipName) {
                $this->validateRelationType($measurements, ['measurements']);

                if (count($measurements->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\Measurement')
                        ->createQueryBuilder('m')
                        ->where((new Expr())->in('m.id', $measurements->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $measurements->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($device->getMeasurements()->count() > 0) {
                    foreach ($device->getMeasurements() as $measurement) {
                        $device->removeMeasurement($measurement);
                    }
                }

                foreach ($association as $measurement) {
                    $device->addMeasurement($measurement);
                }
            },
        ];
    }
}
