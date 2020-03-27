<?php

namespace App\Grocery\Interfaces;

interface WarehouseInterface
{
    public function open(): void;
    public function refresh(): void;
    public function close(): void;
}
