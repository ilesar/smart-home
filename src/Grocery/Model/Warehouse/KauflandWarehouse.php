<?php


namespace App\Grocery\Model\Warehouse;


use App\Grocery\Interfaces\WarehouseInterface;
use App\Grocery\Model\Base\BaseWarehouse;

class KauflandWarehouse extends BaseWarehouse implements WarehouseInterface
{

    public function refreshStock(): void
    {
        // TODO: Implement refresh() method.
    }
}
