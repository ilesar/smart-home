<?php

namespace App\Controller;

use App\Entity\Configuration;
use App\JsonApi\Document\Configuration\ConfigurationDocument;
use App\JsonApi\Document\Configuration\ConfigurationsDocument;
use App\JsonApi\Hydrator\Configuration\CreateConfigurationHydrator;
use App\JsonApi\Hydrator\Configuration\UpdateConfigurationHydrator;
use App\JsonApi\Transformer\ConfigurationResourceTransformer;
use App\Repository\ConfigurationRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/configurations")
 */
class ConfigurationController extends Controller
{
    /**
     * @Route("/", name="configurations_index", methods="GET")
     */
    public function index(ConfigurationRepository $configurationRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($configurationRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationsDocument(new ConfigurationResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="configurations_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $configuration = $this->jsonApi()->hydrate(new CreateConfigurationHydrator($entityManager, $exceptionFactory), new Configuration());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($configuration);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($configuration);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationDocument(new ConfigurationResourceTransformer()),
            $configuration
        );
    }

    /**
     * @Route("/{id}", name="configurations_show", methods="GET")
     */
    public function show(Configuration $configuration): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ConfigurationDocument(new ConfigurationResourceTransformer()),
            $configuration
        );
    }

    /**
     * @Route("/{id}", name="configurations_edit", methods="PATCH")
     */
    public function edit(Configuration $configuration, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $configuration = $this->jsonApi()->hydrate(new UpdateConfigurationHydrator($entityManager, $exceptionFactory), $configuration);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($configuration);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationDocument(new ConfigurationResourceTransformer()),
            $configuration
        );
    }

    /**
     * @Route("/{id}", name="configurations_delete", methods="DELETE")
     */
    public function delete(Request $request, Configuration $configuration): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($configuration);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
