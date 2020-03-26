<?php

namespace App\Grocery;

use App\Grocery\Interfaces\WarehouseInterface;

class GroceryService
{
    /**
     * @var WarehouseInterface[]
     */
    private $warehouses;

    public function __construct($warehouses)
    {
        $this->warehouses = $warehouses;
    }

    public function refreshWarehouses()
    {
        foreach ($this->warehouses as $warehouse) {
            echo $warehouse->getName().PHP_EOL;
            $warehouse->refresh();
        }
    }
}
