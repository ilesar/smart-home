<?php

namespace App\DataFixtures;

use App\Entity\GroceryItem;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GroceryItemFixtures extends Fixture
{
    private const GROCERY_ITEMS = [
        'Cedevita' => 35,
        'Argeta pašteta kokošja' => 7,
        'Kulen 100g' => 14,
        'Jaja 10kom' => 12.39,
        'Masline' => 13,
        'Kristal Šećer' => 5.49,
        'Eva Tuna' => 11.99,
    ];

    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        foreach (self::GROCERY_ITEMS as $itemName => $itemPrice) {
            $groceryItem = new GroceryItem();
            $groceryItem->setName($itemName);
            $groceryItem->setPrice($itemPrice);

            $manager->persist($groceryItem);
        }

        $manager->flush();
    }
}
