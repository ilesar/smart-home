<?php

declare(strict_types=1);

namespace App\JsonApi\Transformer;

use App\Model\JsonWebToken;
use WoohooLabs\Yin\JsonApi\Schema\Link\ResourceLinks;
use WoohooLabs\Yin\JsonApi\Schema\Resource\AbstractResource;

/**
 * Category Resource Transformer.
 */
class JwtResourceTransformer extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    public function getType($jwt): string
    {
        return 'tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function getId($jwt): string
    {
        return date(DATE_ATOM);
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($jwt): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getLinks($jwt): ?ResourceLinks
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($jwt): array
    {
        return [
            'access_token' => function (JsonWebToken $jwt) {
                return $jwt->getAccessToken();
            },
            'token_type' => function () {
                return JsonWebToken::TOKEN_TYPE_BEARER;
            },
            'expires_at' => function (JsonWebToken $jwt) {
                return $jwt->getExpiresAt()->format(DATE_ATOM);
            },
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultIncludedRelationships($jwt): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRelationships($jwt): array
    {
        return [
        ];
    }
}
