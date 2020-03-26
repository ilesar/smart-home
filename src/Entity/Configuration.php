<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigurationRepository")
 */
class Configuration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ConfigurationItem", mappedBy="configuration", orphanRemoval=true)
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ConfigurationTemplate", mappedBy="configuration", orphanRemoval=true)
     */
    private $templates;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Device", mappedBy="configuration", cascade={"persist", "remove"})
     */
    private $device;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->templates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|ConfigurationItem[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ConfigurationItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setConfiguration($this);
        }

        return $this;
    }

    public function removeItem(ConfigurationItem $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getConfiguration() === $this) {
                $item->setConfiguration(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ConfigurationTemplate[]
     */
    public function getTemplates(): Collection
    {
        return $this->templates;
    }

    public function addTemplate(ConfigurationTemplate $template): self
    {
        if (!$this->templates->contains($template)) {
            $this->templates[] = $template;
            $template->setConfiguration($this);
        }

        return $this;
    }

    public function removeTemplate(ConfigurationTemplate $template): self
    {
        if ($this->templates->contains($template)) {
            $this->templates->removeElement($template);
            // set the owning side to null (unless already changed)
            if ($template->getConfiguration() === $this) {
                $template->setConfiguration(null);
            }
        }

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): self
    {
        $this->device = $device;

        // set (or unset) the owning side of the relation if necessary
        $newConfiguration = null === $device ? null : $this;
        if ($device->getConfiguration() !== $newConfiguration) {
            $device->setConfiguration($newConfiguration);
        }

        return $this;
    }
}
