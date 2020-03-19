<?php

namespace App\Controller;

use App\Entity\Device;
use App\JsonApi\Document\Device\DeviceDocument;
use App\JsonApi\Document\Device\DevicesDocument;
use App\JsonApi\Hydrator\Device\CreateDeviceHydrator;
use App\JsonApi\Hydrator\Device\UpdateDeviceHydrator;
use App\JsonApi\Transformer\DeviceResourceTransformer;
use App\Repository\DeviceRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/devices")
 */
class DeviceController extends Controller
{
    /**
     * @Route("/", name="devices_index", methods="GET")
     */
    public function index(DeviceRepository $deviceRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($deviceRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new DevicesDocument(new DeviceResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="devices_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $device = $this->jsonApi()->hydrate(new CreateDeviceHydrator($entityManager, $exceptionFactory), new Device());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($device);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($device);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new DeviceDocument(new DeviceResourceTransformer()),
            $device
        );
    }

    /**
     * @Route("/{id}", name="devices_show", methods="GET")
     */
    public function show(Device $device): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new DeviceDocument(new DeviceResourceTransformer()),
            $device
        );
    }

    /**
     * @Route("/{id}", name="devices_edit", methods="PATCH")
     */
    public function edit(Device $device, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $device = $this->jsonApi()->hydrate(new UpdateDeviceHydrator($entityManager, $exceptionFactory), $device);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($device);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new DeviceDocument(new DeviceResourceTransformer()),
            $device
        );
    }

    /**
     * @Route("/{id}", name="devices_delete", methods="DELETE")
     */
    public function delete(Device $device): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($device);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
