<?php

namespace App\JsonApi\Transformer;

use App\Entity\Image;
use App\Service\ConfigurationService;
use WoohooLabs\Yin\JsonApi\Schema\Link\Link;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Relationship\ToOneRelationship;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Image Resource Transformer.
 */
class ImageResourceTransformer extends AbstractResource
{

    /**
     * @var ConfigurationService
     */
    private $configuration;

    public function __construct(ConfigurationService $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getType($image): string
    {
        return 'images';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($image): string
    {
        return (string) $image->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($image): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($image): ?ResourceLinks
    {
        return ResourceLinks::createWithoutBaseUri()->setSelf(new Link('/images/'.$this->getId($image)));
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($image): array
    {
        return [
            'image' => function (Image $image) {
                return $this->configuration->getImagesStaticUrl($image->getFilename());
            },
            'uuid' => function (Image $image) {
                return $image->getUuid();
            },
            'createdAt' => function (Image $image) {
                return $image->getCreatedAt()->format(DATE_ATOM);
            },
            'updatedAt' => function (Image $image) {
                return $image->getUpdatedAt()->format(DATE_ATOM);
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($image): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($image): array
    {
        return [
            'groceryItem' => function (Image $image) {
                return ToOneRelationship::create()
                    ->setDataAsCallable(
                        function () use ($image) {
                            return $image->getGroceryItem();
                        },
                        new GroceryItemResourceTransformer($this->configuration)
                    )
                    ->omitDataWhenNotIncluded();
            },
        ];
    }
}
