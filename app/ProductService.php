<?php

namespace Warehouse\App;
require 'vendor/autoload.php';

use Carbon\Carbon;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class ProductService
{
    private Warehouse $warehouse;
    private DataManagement $dataManagement;
    private LogService $logService;
    private string $username;

    public function __construct(
        Warehouse      $warehouse,
        DataManagement $dataManagement,
        LogService     $logService,
        string         $username)
    {
        $this->warehouse = $warehouse;
        $this->dataManagement = $dataManagement;
        $this->logService = $logService;
        $this->username = $username;

        $products = $this->dataManagement->loadProducts();
        foreach ($products as $product) {
            $this->warehouse->addProduct($product);
        }
        $this->logService->log("User {$this->username} loaded products into warehouse at " .
            Carbon::now()->toDateTimeString());
    }

    public function createProduct(Product $product): void
    {
        $this->warehouse->addProduct($product);
        $this->saveProducts();
        $this->logService->log(
            "User {$this->username} created product {$product->getName()} (ID: {$product->getId()}) at "
            . Carbon::now()->toDateTimeString());
    }

    public function editProductInfo(string $productId, string $name, int $quantity, float $price): void
    {
        $product = $this->warehouse->getProduct($productId);
        if ($product) {
            $product->setName($name);
            $product->setQuantity($quantity);
            $product->setPrice($price);
            $this->saveProducts();
            $this->logService->log(
                "User {$this->username} updated information for product {$product->getName()} (ID: {$productId})" .
                "to {$quantity} at " . Carbon::now()->toDateTimeString());
        } else {
            echo "\033[31mYou failed to update information!\033[0m\n";
            $this->logService->log(
                "User {$this->username} failed to update information for product with ID: {$productId} at "
                . Carbon::now()->toDateTimeString());
        }
    }

    public function addProductQuantity(string $productId, int $quantity): void
    {
        $product = $this->warehouse->getProduct($productId);
        if ($product) {
            $this->warehouse->addProductQuantity($productId, $quantity);
            $this->saveProducts();
            $this->logService->log(
                "User {$this->username} added quantity of {$quantity} to product {$product->getName()} (ID: {$productId}) at "
                . Carbon::now()->toDateTimeString());
        } else {
            echo "\033[31mYou failed to add quantity!\033[0m\n";
            $this->logService->log(
                "User {$this->username} failed to add quantity for product with ID: {$productId} at "
                . Carbon::now()->toDateTimeString());
        }
    }

    public function reduceProductQuantity(string $productId, int $quantity): void
    {
        $product = $this->warehouse->getProduct($productId);
        if ($product) {
            $this->warehouse->reduceProductQuantity($productId, $quantity);
            $this->saveProducts();
            $this->logService->log(
                "User {$this->username} reduced quantity of {$quantity} from product {$product->getName()} (ID: {$productId}) at "
                . Carbon::now()->toDateTimeString());
        } else {
            echo "\033[31mYou failed to reduce quantity!\033[0m\n";
            $this->logService->log(
                "User {$this->username} failed to reduce quantity for product with ID: {$productId} at "
                . Carbon::now()->toDateTimeString());
        }
    }

    public function removeProduct(string $productId): void
    {
        $product = $this->warehouse->getProduct($productId);
        if ($product && $this->warehouse->deleteProduct($productId)) {
            $this->saveProducts();
            $this->logService->log(
                "User {$this->username} removed product {$product->getName()} (ID: {$productId}) at "
                . Carbon::now()->toDateTimeString());
        } else {
            echo "\033[31mYou failed to remove product!\033[0m\n";
            $this->logService->log(
                "User {$this->username} failed to remove product with ID: {$productId} at " .
                Carbon::now()->toDateTimeString());
        }
    }

    private function saveProducts(): void
    {
        $this->dataManagement->saveProducts($this->warehouse->listProducts());
        $this->logService->log(
            "User {$this->username} saved products to file at " . Carbon::now()->toDateTimeString());
    }

    public function generateReport(): void
    {
        $totalProducts = count($this->warehouse->listProducts());
        $totalValue = array_reduce($this->warehouse->listProducts(), function ($carry, Product $product) {
            return $carry + ($product->getPrice() * $product->getQuantity());
        }, 0);

        $output = new ConsoleOutput();
        $table = new Table($output);
        $table->setHeaders(['Total number of products', 'Total value of all products']);
        $table->addRow([
            $totalProducts,
            $totalValue . " EUR"
        ]);
        $table->render();
        $this->logService->log(
            "User {$this->username} generated product report at " . Carbon::now()->toDateTimeString());
    }
}