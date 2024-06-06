# Warehouse Management System

## Description
A command-line application called Warehouse Management System built in PHP. It allows users to manage products within a warehouse environment. Products are persisted in a JSON file and all activities are logged.

## Features
- **User Authentication**: Users are required to log in with a username and password (test pw available in json file).
- **Product Management**: Users can create, edit, add to, reduce, and remove products from the warehouse inventory.
- **Display Products**: Users can view all products currently stored in the warehouse.
- **Create Report**: Users can generate report to view amount and total value of all products currently stored in the warehouse.

## Installation

### Prerequisites
- PHP 7.4 or higher
- Composer (for autoloading classes)

### Steps
1. Clone the repository:
    ```sh
    git clone https://github.com/smitens/WAREHOUSE.git
    ```

2. Install dependencies:
    ```sh
    composer install
    ```

## Usage
Run the application from the command line:

```sh
php index.php
```
Follow the prompts to log in with one of test usernames and passwords provided. 
Choose from the available menu options to manage products in the warehouse.

## Menu Options

1. **Create Product**: Add a new product to the warehouse inventory.
2. **Edit Product Quantity**: Change the quantity of an existing product.
3. **Add to Product Quantity**: Increase the quantity of an existing product.
4. **Reduce Product Quantity**: Decrease the quantity of an existing product.
5. **Remove Product**: Delete a product from the warehouse inventory.
6. **Display Products**: View all products currently stored in the warehouse.
7. **Generate Product Report**: Generate report to view amount and total value of all products currently stored in the warehouse.
8. **Exit**: Quit the application.
