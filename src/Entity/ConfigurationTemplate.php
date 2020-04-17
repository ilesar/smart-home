<?php

namespace App\Entity;

use App\Entity\Traits\ActivatableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigurationTemplateRepository")
 */
class ConfigurationTemplate
{

    use ActivatableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Configuration", inversedBy="templates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $configuration;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ConfigurationTemplateItem", mappedBy="template")
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConfiguration(): ?Configuration
    {
        return $this->configuration;
    }

    public function setConfiguration(?Configuration $configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * @return Collection|ConfigurationTemplateItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ConfigurationTemplateItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setTemplate($this);
        }

        return $this;
    }

    public function removeItem(ConfigurationTemplateItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getTemplate() === $this) {
                $item->setTemplate(null);
            }
        }

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
