<?php

require_once 'vendor/autoload.php';

use Carbon\Carbon;
use Warehouse\App\AuthService;
use Warehouse\App\Product;
use Warehouse\App\ProductService;
use Warehouse\App\Warehouse;
use Warehouse\App\DataManagement;
use Warehouse\App\LogService;


$warehouse = new Warehouse();
$dataManager = new DataManagement(__DIR__ . '/products.json');
$logService = new LogService();
$authService = new AuthService(__DIR__ . '/users.json');;

$username = trim(strtolower(readline("Username: ")));

echo "Password: ";
system('stty -echo');
$password = trim(fgets(STDIN, 1024));
system('stty echo');
echo "\n";

try {
    $message = $authService->login($username, $password);
    echo $message . "\n";
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
    exit;
}


$productService = new ProductService($warehouse, $dataManager, $logService, $username);

echo "\n\033[1m\033[4mWarehouse Management System\033[0m\n\n";
while (true) {
    displayMenu();
    $choice = trim(readline("Enter the number of your choice: "));

    switch ($choice) {
        case '1':
            createProduct();
            break;
        case '2':
            editProductInfo();
            break;
        case '3':
            addProductQuantity();
            break;
        case '4':
            reduceProductQuantity();
            break;
        case '5':
            removeProduct();
            break;
        case '6':
            displayProducts();
            break;
        case '7':
            generateReport();
            break;
        case '8':
            exit("Exiting Warehouse Management System. Goodbye!\n");
        default:
            echo "\033[31mInvalid choice. Please try again.\033[0m\n";
    }
}

function displayMenu(): void
{
    echo "1. Create Product\n";
    echo "2. Edit Product Information\n";
    echo "3. Add to Product Quantity\n";
    echo "4. Reduce Product Quantity\n";
    echo "5. Remove Product\n";
    echo "6. Display Products\n";
    echo "7. Generate Product Report\n";
    echo "8. Exit\n";
}

function createProduct(): void
{
    global $productService;

    $name = readline("Enter product name: ");
    $quantity = (int) readline("Enter product quantity: ");
    $price = (float) readline("Enter product price in Euro: ");
    $qualityDate = null;
    while (true) {
    $qualityDateInput = readline("Enter product quality expiration date (YYYY-MM-DD, if none, leave empty): ");
    if (empty($qualityDateInput)) {
    break;
    }
        $qualityDate = Carbon::parse($qualityDateInput);
        if ($qualityDate->isPast()) {
            echo "\033[31mError: The date must be in the future.\033[0m\n";
            continue;
        }
        break;
    }
    $createdAt = Carbon::now();
    $product = new Product($name, $quantity, $price, $createdAt, $qualityDate);
    $productService->createProduct($product);
}

function editProductInfo(): void
{
    global $productService;
    global $warehouse;

    $productId = readline("Enter product ID: ");
    $product = $warehouse->getProduct($productId);
    if (!$product) {
        echo "\033[31mProduct not found!\033[0m\n";
        return;
    }
    $name = readline("Enter product name: ");
    $quantity = (int) (trim(readline("Enter new quantity: ")));
    $price = (float) (trim(readline("Enter product price: ")));
    $name = $name ?: $product->getName();
    $quantity = $quantity ?: $product->getQuantity();
    $price = $price ?: $product->getPrice();

    $productService->editProductInfo($productId, $name, $quantity, $price);
}

function addProductQuantity(): void
{
    global $productService;

    $productId = readline("Enter product ID: ");
    $quantity = (int) readline("Enter quantity to add: ");

    $productService->addProductQuantity($productId, $quantity);
}

function reduceProductQuantity(): void
{
    global $productService;

    $productId = readline("Enter product ID: ");
    $quantity = (int) readline("Enter quantity to reduce: ");

    $productService->reduceProductQuantity($productId, $quantity);
}

function removeProduct(): void
{
    global $productService;

    $productId = readline("Enter product ID to remove: ");
    $productService->removeProduct($productId);
}

function displayProducts(): void
{
    global $warehouse;

    echo "Products in Warehouse:\n";
    $warehouse->displayProducts();
}

function generateReport(): void
{
    global $productService;
    echo "Product Report:\n";
    $productService->generateReport();
}