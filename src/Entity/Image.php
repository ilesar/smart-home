<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\EntityListeners({"App\EventListener\FileUploadListener"})
 */
class Image
{
    use UuidTrait;
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var File|null
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\GroceryItem", mappedBy="image", cascade={"persist", "remove"})
     */
    private $groceryItem;

    /**
     * @var UploadedFile|null
     */
    private $uploadedFile;

    public function setUploadedFile(?UploadedFile $uploadedFile): void
    {
        $this->uploadedFile = $uploadedFile;
    }

    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getGroceryItem(): ?GroceryItem
    {
        return $this->groceryItem;
    }

    public function setGroceryItem(?GroceryItem $groceryItem): self
    {
        $this->groceryItem = $groceryItem;

        // set (or unset) the owning side of the relation if necessary
        $newImage = null === $groceryItem ? null : $this;
        if ($groceryItem->getImage() !== $newImage) {
            $groceryItem->setImage($newImage);
        }

        return $this;
    }
}
