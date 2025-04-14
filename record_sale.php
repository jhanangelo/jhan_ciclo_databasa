<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $product_id = $_POST['product_id'];
    $employee_id = $_POST['employee_id'];
    $payment_type = $_POST['payment_type'];

    $stmt = $conn->prepare("INSERT INTO sales (customer_id, product_id, employee_id, payment_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $customer_id, $product_id, $employee_id, $payment_type);

    if ($stmt->execute()) {
        $conn->query("UPDATE products SET status = 'sold' WHERE product_id = $product_id");
        echo "Sale recorded!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
