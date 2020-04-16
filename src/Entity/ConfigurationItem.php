<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigurationItemRepository")
 */
class ConfigurationItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Configuration", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $configuration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $inputType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $outputFormat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ConfigurationTemplateItem", mappedBy="configurationItem", orphanRemoval=true)
     */
    private $configurationTemplateItems;

    public function __construct()
    {
        $this->configurationTemplateItems = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInputType(): ?string
    {
        return $this->inputType;
    }

    public function setInputType(string $inputType): self
    {
        $this->inputType = $inputType;

        return $this;
    }

    public function getOutputFormat(): ?string
    {
        return $this->outputFormat;
    }

    public function setOutputFormat(string $outputFormat): self
    {
        $this->outputFormat = $outputFormat;

        return $this;
    }

    /**
     * @return Collection|ConfigurationTemplateItem[]
     */
    public function getConfigurationTemplateItems(): Collection
    {
        return $this->configurationTemplateItems;
    }

    public function addConfigurationTemplateItem(ConfigurationTemplateItem $configurationTemplateItem): self
    {
        if (!$this->configurationTemplateItems->contains($configurationTemplateItem)) {
            $this->configurationTemplateItems[] = $configurationTemplateItem;
            $configurationTemplateItem->setConfigurationItem($this);
        }

        return $this;
    }

    public function removeConfigurationTemplateItem(ConfigurationTemplateItem $configurationTemplateItem): self
    {
        if ($this->configurationTemplateItems->contains($configurationTemplateItem)) {
            $this->configurationTemplateItems->removeElement($configurationTemplateItem);
            // set the owning side to null (unless already changed)
            if ($configurationTemplateItem->getConfigurationItem() === $this) {
                $configurationTemplateItem->setConfigurationItem(null);
            }
        }

        return $this;
    }
}
