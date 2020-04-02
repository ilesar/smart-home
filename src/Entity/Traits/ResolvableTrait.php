<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait ResolvableTrait.
 *
 * @ORM\HasLifecycleCallbacks()
 */
trait ResolvableTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(name="is_resolved", type="boolean")
     */
    private $isResolved = false;

    public function getIsResolved(): bool
    {
        return $this->isResolved;
    }

    public function setIsResolved(bool $isResolved): void
    {
        $this->isResolved = $isResolved;
    }
}
