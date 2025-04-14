<!-- product_input.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { max-width: 400px; margin: auto; }
        input, button { display: block; width: 100%; margin-bottom: 15px; padding: 10px; }
    </style>
</head>
<body>
    <h2>Add New Product</h2>
    <form method="POST" action="add_product.php">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" step="0.01" name="price" placeholder="Price (e.g. 4999.99)" required>
        <input type="number" name="stock" placeholder="Stock Quantity" required>
        <button type="submit">Add Product</button>
    </form>
</body>
</html>

<?php
// add_product.php
$conn = new mysqli("localhost", "root", "", "ciclo_suerte");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$name = $_POST['name'] ?? '';
$price = $_POST['price'] ?? 0;
$stock = $_POST['stock'] ?? 0;

// Prepare and execute insert
$stmt = $conn->prepare("INSERT INTO products (name, price, stock) VALUES (?, ?, ?)");
$stmt->bind_param("sdi", $name, $price, $stock);

if ($stmt->execute()) {
    echo "✅ Product added successfully!<br><a href='product_input.php'>Add another product</a>";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>