<?php


namespace App\Grocery\ValueObject;


class KonzumCategory
{


    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $link;

    public function __construct(string $name, string $link)
    {

        $this->name = $name;
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }


}
