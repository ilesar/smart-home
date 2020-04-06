<?php

namespace App\Controller;

use App\Entity\Room;
use App\JsonApi\Document\Room\RoomDocument;
use App\JsonApi\Document\Room\RoomsDocument;
use App\JsonApi\Hydrator\Room\CreateRoomHydrator;
use App\JsonApi\Hydrator\Room\UpdateRoomHydrator;
use App\JsonApi\Transformer\RoomResourceTransformer;
use App\Repository\RoomRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/rooms")
 */
class RoomController extends Controller
{
    /**
     * @Route("", name="rooms_index", methods="GET")
     */
    public function index(RoomRepository $roomRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($roomRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new RoomsDocument(new RoomResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("", name="rooms_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $room = $this->jsonApi()->hydrate(new CreateRoomHydrator($entityManager, $exceptionFactory), new Room());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($room);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($room);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new RoomDocument(new RoomResourceTransformer()),
            $room
        );
    }

    /**
     * @Route("/{id}", name="rooms_show", methods="GET")
     */
    public function show(Room $room): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new RoomDocument(new RoomResourceTransformer()),
            $room
        );
    }

    /**
     * @Route("/{id}", name="rooms_edit", methods="PATCH")
     */
    public function edit(Room $room, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $room = $this->jsonApi()->hydrate(new UpdateRoomHydrator($entityManager, $exceptionFactory), $room);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($room);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new RoomDocument(new RoomResourceTransformer()),
            $room
        );
    }

    /**
     * @Route("/{id}", name="rooms_delete", methods="DELETE")
     */
    public function delete(Room $room): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($room);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
