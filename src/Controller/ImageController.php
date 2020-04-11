<?php

namespace App\Controller;

use App\Entity\Image;
use App\JsonApi\Document\Image\ImageDocument;
use App\JsonApi\Document\Image\ImagesDocument;
use App\JsonApi\Hydrator\Image\CreateImageHydrator;
use App\JsonApi\Hydrator\Image\UpdateImageHydrator;
use App\JsonApi\Transformer\ImageResourceTransformer;
use App\Repository\ImageRepository;
use Paknahad\JsonApiBundle\Controller\Controller;
use Paknahad\JsonApiBundle\Helper\ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use WoohooLabs\Yin\JsonApi\Exception\DefaultExceptionFactory;

/**
 * @Route("/images")
 */
class ImageController extends Controller
{
    /**
     * @Route("", name="images_index", methods="GET")
     */
    public function index(ImageRepository $imageRepository, ResourceCollection $resourceCollection): ResponseInterface
    {
        $resourceCollection->setRepository($imageRepository);

        $resourceCollection->handleIndexRequest();

        return $this->jsonApi()->respond()->ok(
            new ImagesDocument(new ImageResourceTransformer()),
            $resourceCollection
        );
    }

    /**
     * @Route("", name="images_new", methods="POST")
     */
    public function new(ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory, Request $request, ImageResourceTransformer $imageResourceTransformer): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $file = $request->files->get('image');
        $image = new Image();
        $image->setUploadedFile($file);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($image);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->persist($image);
        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ImageDocument($imageResourceTransformer),
            $image
        );
    }

    /**
     * @Route("/{id}", name="images_show", methods="GET")
     */
    public function show(Image $image): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new ImageDocument(new ImageResourceTransformer()),
            $image
        );
    }

    /**
     * @Route("/{id}", name="images_edit", methods="PATCH")
     */
    public function edit(Image $image, ValidatorInterface $validator, DefaultExceptionFactory $exceptionFactory): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();

        $image = $this->jsonApi()->hydrate(new UpdateImageHydrator($entityManager, $exceptionFactory), $image);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($image);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $entityManager->flush();

        return $this->jsonApi()->respond()->ok(
            new ImageDocument(new ImageResourceTransformer()),
            $image
        );
    }

    /**
     * @Route("/{id}", name="images_delete", methods="DELETE")
     */
    public function delete(Image $image): ResponseInterface
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($image);
        $entityManager->flush();

        return $this->jsonApi()->respond()->genericSuccess(204);
    }
}
