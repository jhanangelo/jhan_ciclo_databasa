<?php
$conn = new mysqli("localhost", "root", "", "ciclo_suerte");

$sale_id = $_GET['sale_id'] ?? 0;

$query = "
    SELECT 
        s.id AS sale_id, 
        c.name AS customer_name,
        c.contact,
        p.name AS product_name,
        s.quantity,
        p.price,
        s.total_price,
        s.payment_type,
        s.sale_date,
        i.months_total,
        i.monthly_payment
    FROM sales s
    JOIN customers c ON s.customer_id = c.id
    JOIN products p ON s.product_id = p.id
    LEFT JOIN installments i ON s.id = i.sale_id
    WHERE s.id = $sale_id
";

$result = $conn->query($query);
if (!$result || $result->num_rows == 0) {
    die("❌ Sale not found.");
}
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipt #<?= $row['sale_id'] ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; max-width: 500px; margin: auto; }
        h2 { text-align: center; }
        .receipt-box { border: 1px solid #ccc; padding: 20px; }
        .line { margin-bottom: 10px; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="receipt-box">
        <h2>SUERTE MOTOPLAZA</h2>
        <div class="line"><span class="bold">Receipt No:</span> <?= $row['sale_id'] ?></div>
        <div class="line"><span class="bold">Date:</span> <?= $row['sale_date'] ?></div>

        <hr>

        <div class="line"><span class="bold">Customer:</span> <?= $row['customer_name'] ?></div>
        <div class="line"><span class="bold">Contact:</span> <?= $row['contact'] ?></div>

        <hr>

        <div class="line"><span class="bold">Product:</span> <?= $row['product_name'] ?></div>
        <div class="line"><span class="bold">Price per Item:</span> ₱<?= number_format($row['price'], 2) ?></div>
        <div class="line"><span class="bold">Quantity:</span> <?= $row['quantity'] ?></div>
        <div class="line"><span class="bold">Total Price:</span> ₱<?= number_format($row['total_price'], 2) ?></div>
        <div class="line"><span class="bold">Payment Type:</span> <?= ucfirst($row['payment_type']) ?></div>

        <?php if ($row['payment_type'] === 'installment'): ?>
            <div class="line"><span class="bold">Monthly Payment:</span> ₱<?= number_format($row['monthly_payment'], 2) ?></div>
            <div class="line"><span class="bold">Months:</span> <?= $row['months_total'] ?></div>
        <?php endif; ?>

        <hr>
        <p style="text-align:center;">Thank you for your purchase!</p>

        <div style="text-align:center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px;">🖨️ Print Receipt</button>
</div>