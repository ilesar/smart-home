<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProxyServerRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProxyServer
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
     * @Assert\Ip
     */
    private $host;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\LessThanOrEqual(65535)
     * @Assert\GreaterThanOrEqual(0)
     */
    private $port;

    /**
     * @ORM\Column(type="integer")
     */
    private $attempts = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBlacklisted = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $blacklistedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isWhitelisted = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $whitelistedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getPort(): ?string
    {
        return $this->port;
    }

    public function setPort(string $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): self
    {
        $this->attempts = $attempts;

        return $this;
    }

    public function getIsBlacklisted(): ?bool
    {
        return $this->isBlacklisted;
    }

    public function setIsBlacklisted(bool $isBlacklisted): self
    {
        $this->isBlacklisted = $isBlacklisted;

        return $this;
    }

    public function getBlacklistedAt(): ?\DateTimeInterface
    {
        return $this->blacklistedAt;
    }

    public function setBlacklistedAt(?\DateTimeInterface $blacklistedAt): self
    {
        $this->blacklistedAt = $blacklistedAt;

        return $this;
    }

    public function getIsWhitelisted(): ?bool
    {
        return $this->isWhitelisted;
    }

    public function setIsWhitelisted(bool $isWhitelisted): self
    {
        $this->isWhitelisted = $isWhitelisted;

        return $this;
    }

    public function getWhitelistedAt(): ?\DateTimeInterface
    {
        return $this->whitelistedAt;
    }

    public function setWhitelistedAt(?\DateTimeInterface $whitelistedAt): self
    {
        $this->whitelistedAt = $whitelistedAt;

        return $this;
    }
}
