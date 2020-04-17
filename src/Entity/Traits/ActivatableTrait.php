<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait ActivatableTrait
{

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isActive = false;


    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }
}
