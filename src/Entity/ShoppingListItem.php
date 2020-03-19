<?php

namespace App\Entity;

use App\Entity\Traits\DeletableTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShoppingListItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ShoppingListItem
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
     * @ORM\ManyToOne(targetEntity="App\Entity\GroceryItem", inversedBy="shoppingListItems")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groceryItem;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroceryItem(): ?GroceryItem
    {
        return $this->groceryItem;
    }

    public function setGroceryItem(?GroceryItem $groceryItem): self
    {
        $this->groceryItem = $groceryItem;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
