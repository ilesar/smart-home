<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConfigurationService
{
    private const GROCERY_IMAGE_PATH = 'grocery.image.path';
    private const STATIC_URL = 'global.static.url';
    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function getGroceryImagePath()
    {
        return vsprintf('%s/%s', [
            $this->params->get(self::GROCERY_IMAGE_PATH),
            'grocery/images',
        ]);
    }

    public function getImagesStaticUrl(string $imageFilename)
    {
        return vsprintf('%s/%s/%s', [
            $this->params->get(self::STATIC_URL),
            'grocery/images',
            $imageFilename,
        ]);
    }
}
