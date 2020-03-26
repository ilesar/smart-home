<?php

namespace App\Controller;

use App\Entity\ConfigurationItem;
use App\JsonApi\Document\ConfigurationItem\ConfigurationItemDocument;
use App\JsonApi\Document\ConfigurationItem\ConfigurationItemsDocument;
use App\JsonApi\Hydrator\ConfigurationItem\CreateConfigurationItemHydrator;
use App\JsonApi\Hydrator\ConfigurationItem\UpdateConfigurationItemHydrator;
use App\JsonApi\Transformer\ConfigurationItemResourceTransformer;
use App\Repository\ConfigurationItemRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/configuration/items")
 */
class ConfigurationItemController extends Controller
{
    /**
     * @Route("/", name="configuration_items_index", methods="GET")
     */
    public function index(ConfigurationItemRepository $configurationItemRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($configurationItemRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationItemsDocument(new ConfigurationItemResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="configuration_items_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $configurationItem = $this->jsonApi()->hydrate(new CreateConfigurationItemHydrator($entityManager, $exceptionFactory), new ConfigurationItem());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($configurationItem);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($configurationItem);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationItemDocument(new ConfigurationItemResourceTransformer()),
            $configurationItem
        );
    }

    /**
     * @Route("/{id}", name="configuration_items_show", methods="GET")
     */
    public function show(ConfigurationItem $configurationItem): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ConfigurationItemDocument(new ConfigurationItemResourceTransformer()),
            $configurationItem
        );
    }

    /**
     * @Route("/{id}", name="configuration_items_edit", methods="PATCH")
     */
    public function edit(ConfigurationItem $configurationItem, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $configurationItem = $this->jsonApi()->hydrate(new UpdateConfigurationItemHydrator($entityManager, $exceptionFactory), $configurationItem);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($configurationItem);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationItemDocument(new ConfigurationItemResourceTransformer()),
            $configurationItem
        );
    }

    /**
     * @Route("/{id}", name="configuration_items_delete", methods="DELETE")
     */
    public function delete(Request $request, ConfigurationItem $configurationItem): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($configurationItem);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
