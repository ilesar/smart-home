<?php


namespace App\Grocery\Model\Warehouse;


use App\Grocery\Interfaces\WarehouseInterface;
use App\Grocery\Model\Base\BaseWarehouse;

class KonzumWarehouse extends BaseWarehouse implements WarehouseInterface
{

    private const CATEGORY_PAGE = 'https://www.konzum.hr/kreni-u-kupnju';

    public function refresh(): void
    {
        // TODO: Implement refresh() method.
    }
}
