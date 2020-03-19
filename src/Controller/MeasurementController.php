<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\JsonApi\Document\Measurement\MeasurementDocument;
use App\JsonApi\Document\Measurement\MeasurementsDocument;
use App\JsonApi\Hydrator\Measurement\CreateMeasurementHydrator;
use App\JsonApi\Hydrator\Measurement\UpdateMeasurementHydrator;
use App\JsonApi\Transformer\MeasurementResourceTransformer;
use App\Repository\MeasurementRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/measurements")
 */
class MeasurementController extends Controller
{
    /**
     * @Route("/", name="measurements_index", methods="GET")
     */
    public function index(MeasurementRepository $measurementRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($measurementRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new MeasurementsDocument(new MeasurementResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="measurements_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $measurement = $this->jsonApi()->hydrate(new CreateMeasurementHydrator($entityManager, $exceptionFactory), new Measurement());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($measurement);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($measurement);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new MeasurementDocument(new MeasurementResourceTransformer()),
            $measurement
        );
    }

    /**
     * @Route("/{id}", name="measurements_show", methods="GET")
     */
    public function show(Measurement $measurement): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new MeasurementDocument(new MeasurementResourceTransformer()),
            $measurement
        );
    }

    /**
     * @Route("/{id}", name="measurements_edit", methods="PATCH")
     */
    public function edit(Measurement $measurement, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $measurement = $this->jsonApi()->hydrate(new UpdateMeasurementHydrator($entityManager, $exceptionFactory), $measurement);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($measurement);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new MeasurementDocument(new MeasurementResourceTransformer()),
            $measurement
        );
    }

    /**
     * @Route("/{id}", name="measurements_delete", methods="DELETE")
     */
    public function delete(Measurement $measurement): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($measurement);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
