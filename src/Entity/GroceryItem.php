<?php

namespace App\Entity;

use App\Entity\Traits\DeletableTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GroceryItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class GroceryItem
{
    use TimestampableTrait;

    use DeletableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ShoppingListItem", mappedBy="groceryItem")
     */
    private $shoppingListItems;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", inversedBy="groceryItem", cascade={"persist", "remove"})
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $source;

    public function __construct()
    {
        $this->shoppingListItems = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|ShoppingListItem[]
     */
    public function getShoppingListItems(): Collection
    {
        return $this->shoppingListItems;
    }

    public function addShoppingListItem(ShoppingListItem $shoppingListItem): self
    {
        if (!$this->shoppingListItems->contains($shoppingListItem)) {
            $this->shoppingListItems[] = $shoppingListItem;
            $shoppingListItem->setGroceryItem($this);
        }

        return $this;
    }

    public function removeShoppingListItem(ShoppingListItem $shoppingListItem): self
    {
        if ($this->shoppingListItems->contains($shoppingListItem)) {
            $this->shoppingListItems->removeElement($shoppingListItem);
            // set the owning side to null (unless already changed)
            if ($shoppingListItem->getGroceryItem() === $this) {
                $shoppingListItem->setGroceryItem(null);
            }
        }

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }
}
