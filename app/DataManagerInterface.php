<?php
namespace Warehouse\App;

interface DataManagerInterface
{
    public function loadProducts(): array;
    public function saveProducts(array $products): void;
}
