<?php

namespace App\Grocery\Interfaces;

interface WarehouseInterface
{
    public function open(): void;
    public function refreshStock(): void;
    public function close(): void;
    public function fetchImagesForStock(): void;
}
