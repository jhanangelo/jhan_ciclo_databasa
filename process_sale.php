<?php
// Enable full error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to DB
$conn = new mysqli("localhost", "root", "", "ciclo_suerte");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize and validate inputs
$customer_name = $_POST['customer_name'] ?? '';
$contact = $_POST['contact'] ?? '';
$product_id = $_POST['product_id'] ?? '';
$quantity = $_POST['quantity'] ?? 0;
$payment_type = $_POST['payment_type'] ?? '';
$months = $_POST['months'] ?? null;

// Check if product_id is valid
if (empty($product_id)) {
    die("Product ID is missing.");
}

// Make sure product exists
$product_result = $conn->query("SELECT * FROM products WHERE id = " . (int)$product_id);
if (!$product_result || $product_result->num_rows === 0) {
    die("Product not found.");
}

$product = $product_result->fetch_assoc();

// Check stock
if ($quantity > $product['stock']) {
    die("Not enough stock available.");
}

// Calculate total or installment (optional for later use)
$price = $product['price']; // Assuming there is a 'price' column
$total = $price * $quantity;

// INSERT SALE RECORD
$stmt = $conn->prepare("INSERT INTO sales (customer_name, contact, product_id, quantity, payment_type, months) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssiisi", $customer_name, $contact, $product_id, $quantity, $payment_type, $months);
$stmt->execute();

// UPDATE PRODUCT STOCK
$new_stock = $product['stock'] - $quantity;
$conn->query("UPDATE products SET stock = $new_stock WHERE id = " . (int)$product_id);

echo "Sale recorded successfully!";
?>
