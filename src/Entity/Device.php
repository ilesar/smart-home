<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Device
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="devices")
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Measurement", mappedBy="device")
     */
    private $measurements;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Configuration", inversedBy="device", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $configuration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deviceType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deviceId;

    public function __construct()
    {
        $this->measurements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection|Measurement[]
     */
    public function getMeasurements(): Collection
    {
        return $this->measurements;
    }

    public function addMeasurement(Measurement $measurement): self
    {
        if (!$this->measurements->contains($measurement)) {
            $this->measurements[] = $measurement;
            $measurement->setDevice($this);
        }

        return $this;
    }

    public function removeMeasurement(Measurement $measurement): self
    {
        if ($this->measurements->contains($measurement)) {
            $this->measurements->removeElement($measurement);
            // set the owning side to null (unless already changed)
            if ($measurement->getDevice() === $this) {
                $measurement->setDevice(null);
            }
        }

        return $this;
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

    public function getDeviceType(): ?string
    {
        return $this->deviceType;
    }

    public function setDeviceType(string $deviceType): self
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(string $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function getActiveTemplate()
    {
        foreach ($this->getConfiguration()->getTemplates() as $template) {
            if (true === $template->getIsActive()) {
                return $template;
            }
        }
    }
}
