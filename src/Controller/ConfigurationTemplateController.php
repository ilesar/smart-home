<?php

namespace App\Controller;

use App\Entity\ConfigurationTemplate;
use App\JsonApi\Document\ConfigurationTemplate\ConfigurationTemplateDocument;
use App\JsonApi\Document\ConfigurationTemplate\ConfigurationTemplatesDocument;
use App\JsonApi\Hydrator\ConfigurationTemplate\CreateConfigurationTemplateHydrator;
use App\JsonApi\Hydrator\ConfigurationTemplate\UpdateConfigurationTemplateHydrator;
use App\JsonApi\Transformer\ConfigurationTemplateResourceTransformer;
use App\Repository\ConfigurationTemplateRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/configuration/templates")
 */
class ConfigurationTemplateController extends Controller
{
    /**
     * @Route("", name="configuration_templates_index", methods="GET")
     */
    public function index(ConfigurationTemplateRepository $configurationTemplateRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($configurationTemplateRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationTemplatesDocument(new ConfigurationTemplateResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("", name="configuration_templates_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $configurationTemplate = $this->jsonApi()->hydrate(new CreateConfigurationTemplateHydrator($entityManager, $exceptionFactory), new ConfigurationTemplate());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($configurationTemplate);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($configurationTemplate);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationTemplateDocument(new ConfigurationTemplateResourceTransformer()),
            $configurationTemplate
        );
    }

    /**
     * @Route("/{id}", name="configuration_templates_show", methods="GET")
     */
    public function show(ConfigurationTemplate $configurationTemplate): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ConfigurationTemplateDocument(new ConfigurationTemplateResourceTransformer()),
            $configurationTemplate
        );
    }

    /**
     * @Route("/{id}", name="configuration_templates_edit", methods="PATCH")
     */
    public function edit(ConfigurationTemplate $configurationTemplate, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $configurationTemplate = $this->jsonApi()->hydrate(new UpdateConfigurationTemplateHydrator($entityManager, $exceptionFactory), $configurationTemplate);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($configurationTemplate);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ConfigurationTemplateDocument(new ConfigurationTemplateResourceTransformer()),
            $configurationTemplate
        );
    }

    /**
     * @Route("/{id}", name="configuration_templates_delete", methods="DELETE")
     */
    public function delete(Request $request, ConfigurationTemplate $configurationTemplate): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($configurationTemplate);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
