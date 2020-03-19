<?php

namespace App\JsonApi\Hydrator\Room;

use App\Entity\Room;
use Doctrine\ORM\Query\Expr;
use Paknahad\JsonApiBundle\Hydrator\AbstractHydrator;
use Paknahad\JsonApiBundle\Hydrator\ValidatorTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WoohooLabs\Yin\JsonApi\Exception\ExceptionFactoryInterface;
use WoohooLabs\Yin\JsonApi\Hydrator\Relationship\ToManyRelationship;
use WoohooLabs\Yin\JsonApi\Request\JsonApiRequestInterface;

/**
 * Abstract Room Hydrator.
 */
abstract class AbstractRoomHydrator extends AbstractHydrator
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
        return ['rooms'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeHydrator($room): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateRequest(JsonApiRequestInterface $request): void
    {
        $this->validateFields($this->objectManager->getClassMetadata(Room::class), $request);
    }

    /**
     * {@inheritdoc}
     */
    protected function setId($room, string $id): void
    {
        if ($id && (string) $room->getId() !== $id) {
            throw new NotFoundHttpException('both ids in url & body must be the same');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getRelationshipHydrator($room): array
    {
        return [
            'devices' => function (Room $room, ToManyRelationship $devices, $data, $relationshipName) {
                $this->validateRelationType($devices, ['devices']);

                if (count($devices->getResourceIdentifierIds()) > 0) {
                    $association = $this->objectManager->getRepository('App\Entity\Device')
                        ->createQueryBuilder('d')
                        ->where((new Expr())->in('d.id', $devices->getResourceIdentifierIds()))
                        ->getQuery()
                        ->getResult();

                    $this->validateRelationValues($association, $devices->getResourceIdentifierIds(), $relationshipName);
                } else {
                    $association = [];
                }

                if ($room->getDevices()->count() > 0) {
                    foreach ($room->getDevices() as $device) {
                        $room->removeDevice($device);
                    }
                }

                foreach ($association as $device) {
                    $room->addDevice($device);
                }
            },
        ];
    }
}
