<?php
namespace Warehouse\App;
require 'vendor/autoload.php';

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class Warehouse
{
    private array $products;

    public function __construct(array $initialProducts = [])
    {
        $this->products = $initialProducts;
    }

    public function addProduct(Product $product): void
    {
        $this->products[$product->getId()] = $product;
    }

    public function getProduct(string $productId): ?Product
    {
        return $this->products[$productId] ?? null;
    }

    public function updateProductQuantity(string $productId, int $quantity): void
    {
        if (isset($this->products[$productId])) {
            $this->products[$productId]->setQuantity($quantity);
        }
    }

    public function addProductQuantity(string $productId, int $quantity): void
    {
        if (isset($this->products[$productId])) {
            $this->products[$productId]->addQuantity($quantity);
        }
    }

    public function reduceProductQuantity(string $productId, int $quantity): void
    {
        if (isset($this->products[$productId])) {
            $this->products[$productId]->reduceQuantity($quantity);
        }
    }

    public function deleteProduct(string $productId): bool
    {
        if (isset($this->products[$productId])) {
            unset($this->products[$productId]);
            return true;
        }
        return false;
    }

    private function showProducts(): void
    {
        $output = new ConsoleOutput();

        $table = new Table($output);
        $table->setHeaders(['ID', 'Name', 'Quantity', 'CreatedAt', 'UpdatedAt']);

        foreach ($this->products as $product) {
            $table->addRow([
                $product->getId(),
                $product->getName(),
                $product->getQuantity(),
                $product->getCreatedAt()->toDateTimeString(),
                $product->getUpdatedAt() ? $product->getUpdatedAt()->toDateTimeString() : null,
            ]);
        }
        $table->render();
    }

    public function displayProducts(): void {
        $this->showProducts();
    }
}