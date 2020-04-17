<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConfigurationTemplateItemRepository")
 */
class ConfigurationTemplateItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ConfigurationItem", inversedBy="configurationTemplateItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $configurationItem;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ConfigurationTemplate", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $template;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getConfigurationItem(): ?ConfigurationItem
    {
        return $this->configurationItem;
    }

    public function setConfigurationItem(?ConfigurationItem $configurationItem): self
    {
        $this->configurationItem = $configurationItem;

        return $this;
    }

    public function getTemplate(): ?ConfigurationTemplate
    {
        return $this->template;
    }

    public function setTemplate(?ConfigurationTemplate $template): self
    {
        $this->template = $template;

        return $this;
    }
}
