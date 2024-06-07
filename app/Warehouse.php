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

    public function updateProductInfo(string $productId, string $name, int $quantity, float $price): void
    {
        if (isset($this->products[$productId])) {
            $this->products[$productId]->setName($name);
            $this->products[$productId]->setQuantity($quantity);
            $this->products[$productId]->setPrice($price);
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

    public function listProducts(): array
    {
        return $this->products;
    }

    private function showProducts(): void
    {
        $output = new ConsoleOutput();

        $table = new Table($output);
        $table->setHeaders(['ID', 'Name', 'Quantity', 'Price', 'CreatedAt', 'Quality expiration date', 'UpdatedAt']);

        foreach ($this->products as $product) {
            $table->addRow([
                $product->getId(),
                $product->getName(),
                $product->getQuantity(),
                number_format($product->getPrice(), 2),
                $product->getCreatedAt()->format('Y-m-d H:i:s'),
                $product->getQualityDate() ? $product->getQualityDate()->format('Y-m-d'): null,
                $product->getUpdatedAt() ? $product->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            ]);
        }
        $table->render();
    }

    public function displayProducts(): void {
        $this->showProducts();
    }
}