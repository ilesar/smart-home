<?php


namespace App\Grocery\Model\Warehouse;


use App\Grocery\Interfaces\WarehouseInterface;
use App\Grocery\Model\Base\BaseWarehouse;

class KonzumWarehouse extends BaseWarehouse implements WarehouseInterface
{

    private const CATEGORY_PAGE = '/kreni-u-kupnju';

    public function refreshStock(): void
    {
        $categoriesPage = $this->getInventoryService()->getPage(self::CATEGORY_PAGE, '#content-start > div:nth-child(6) > section:nth-child(11) > button > span');
    }
}
