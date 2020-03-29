<?php


namespace App\Grocery\ValueObject;


class KonzumProduct
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $imageLink;

    public function __construct(string $name, float $price, string $imageLink)
    {

        $this->name = $name;
        $this->price = $price;
        $this->imageLink = $imageLink;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getImageLink(): string
    {
        return $this->imageLink;
    }




}
