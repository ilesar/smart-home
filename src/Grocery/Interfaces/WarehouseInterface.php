<?php

namespace App\Grocery\Interfaces;

interface WarehouseInterface
{
    public function refresh(): void;
}
