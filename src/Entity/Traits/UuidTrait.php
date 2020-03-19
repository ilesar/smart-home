<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Ramsey\Uuid\Uuid;

/**
 * Trait UuidTrait.
 */
trait UuidTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uuid;

    /**
     * @ORM\PrePersist()
     */
    public function getUuid(): string
    {
        if (!$this->uuid) {
            $this->uuid = Uuid::uuid4()
                ->toString();
        }

        return $this->uuid;
    }

    public function setCustomUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }
}
