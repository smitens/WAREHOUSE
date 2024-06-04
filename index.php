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
system('stty echo'); // Show password input
echo PHP_EOL; // Newline after password input

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
            editProductQuantity();
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
            exit("Exiting Warehouse Management System. Goodbye!\n");
        default:
            echo "\033[31mInvalid choice. Please try again.\033[0m\n";
    }
}

function displayMenu(): void
{
    echo "1. Create Product\n";
    echo "2. Edit Product Quantity\n";
    echo "3. Add to Product Quantity\n";
    echo "4. Reduce Product Quantity\n";
    echo "5. Remove Product\n";
    echo "6. Display Products\n";
    echo "7. Exit\n";
}

function createProduct(): void
{
    global $productService;

    $name = readline("Enter product name: ");
    $quantity = (int) readline("Enter product quantity: ");

    $createdAt = Carbon::now();
    $product = new Product($name, $quantity, $createdAt);
    $productService->createProduct($product);
}

function editProductQuantity(): void
{
    global $productService;

    $productId = readline("Enter product ID: ");
    $quantity = (int) readline("Enter new quantity: ");

    $productService->editProductQuantity($productId, $quantity);
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