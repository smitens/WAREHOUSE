<?php

namespace Warehouse\App;
require 'vendor/autoload.php';

class DataManagement
{
    private string $fileJson;

    public function __construct(string $fileJson)
    {
        $this->fileJson = $fileJson;
    }

    public function loadProducts(): array
    {
        if (file_exists($this->fileJson)) {
            $json = file_get_contents($this->fileJson);
            $productData = json_decode($json, true);
            return array_map(['Warehouse\App\Product', 'deserialize'], $productData);
        }
        return [];
    }

    public function saveProducts(array $products): void {
        $json = json_encode($products, JSON_PRETTY_PRINT);
        file_put_contents($this->fileJson, $json);
    }
}