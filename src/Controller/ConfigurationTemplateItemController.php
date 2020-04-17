<?php

namespace App\Controller;

use App\Entity\ConfigurationTemplateItem;
use App\JsonApi\Document\ConfigurationTemplateItem\ConfigurationTemplateItemDocument;
use App\JsonApi\Document\ConfigurationTemplateItem\ConfigurationTemplateItemsDocument;
use App\JsonApi\Hydrator\ConfigurationTemplateItem\CreateConfigurationTemplateItemHydrator;
use App\JsonApi\Hydrator\ConfigurationTemplateItem\UpdateConfigurationTemplateItemHydrator;
use App\JsonApi\Transformer\ConfigurationTemplateItemResourceTransformer;
use App\Repository\ConfigurationTemplateItemRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/configuration/template/items")
 */
class ConfigurationTemplateItemController extends Controller
{
    /**
     * @Route("/", name="configuration_template_items_index", methods="GET")
     */
    public function index(ConfigurationTemplateItemRepository $configurationTemplateItemRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($configurationTemplateItemRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationTemplateItemsDocument(new ConfigurationTemplateItemResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("/", name="configuration_template_items_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $configurationTemplateItem = $this->jsonApi()->hydrate(new CreateConfigurationTemplateItemHydrator($entityManager, $exceptionFactory), new ConfigurationTemplateItem());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($configurationTemplateItem);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($configurationTemplateItem);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationTemplateItemDocument(new ConfigurationTemplateItemResourceTransformer()),
            $configurationTemplateItem
        );
    }

    /**
     * @Route("/{id}", name="configuration_template_items_show", methods="GET")
     */
    public function show(ConfigurationTemplateItem $configurationTemplateItem): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ConfigurationTemplateItemDocument(new ConfigurationTemplateItemResourceTransformer()),
            $configurationTemplateItem
        );
    }

    /**
     * @Route("/{id}", name="configuration_template_items_edit", methods="PATCH")
     */
    public function edit(ConfigurationTemplateItem $configurationTemplateItem, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $configurationTemplateItem = $this->jsonApi()->hydrate(new UpdateConfigurationTemplateItemHydrator($entityManager, $exceptionFactory), $configurationTemplateItem);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($configurationTemplateItem);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationTemplateItemDocument(new ConfigurationTemplateItemResourceTransformer()),
            $configurationTemplateItem
        );
    }

    /**
     * @Route("/{id}", name="configuration_template_items_delete", methods="DELETE")
     */
    public function delete(Request $request, ConfigurationTemplateItem $configurationTemplateItem): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($configurationTemplateItem);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
