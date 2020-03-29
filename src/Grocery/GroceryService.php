<?php

namespace App\Grocery;

use App\Grocery\Interfaces\WarehouseInterface;
use Exception;

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
            $warehouse->open();

            try {
                $warehouse->refreshStock();
            } catch (Exception $exception) {
                throw $exception;
            } finally {
                $warehouse->close();
            }


        }
    }
}
