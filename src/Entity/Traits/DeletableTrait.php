<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DeletableTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(name="is_dleted", type="boolean")
     */
    private $isDeleted = false;

    public function delete(): void
    {
        $this->isDeleted = true;
    }

    public function getIsDeleted(): bool
    {
        return $this->isDeleted;
    }
}
