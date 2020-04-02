<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConfigurationService
{
    private const GROCERY_IMAGE_PATH = 'grocery.image.path';
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
        return $this->params->get(self::GROCERY_IMAGE_PATH);
    }
}
