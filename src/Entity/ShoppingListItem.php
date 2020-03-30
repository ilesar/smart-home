<?php

namespace App\Entity;

use App\Entity\Traits\DeletableTrait;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShoppingListItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ShoppingListItem
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GroceryItem", inversedBy="shoppingListItems")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $groceryItem;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive()
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

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->groceryItem = null;
    }
}
