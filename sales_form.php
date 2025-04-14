<?php
// Optional: Hide warnings
error_reporting(E_ERROR | E_PARSE);

// Connect to DB
$conn = new mysqli("localhost", "root", "", "ciclo_suerte");

// Fetch products
$products = $conn->query("SELECT * FROM products");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>New Sale</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="menu.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { max-width: 500px; margin: auto; }
        input, select, button { width: 100%; margin-bottom: 15px; padding: 10px; }
    </style>
</head>
<body>
    <header style="background-color: black;">
        <img src="FB_IMG_1744374036883.jpg" alt="Header Image" height="65px" width="105px" style="margin-left: 5px; text-align: left; border-radius: 100px;">
        <nav class="navigation">
            <a href="menu.html" style="color: beige;">menu</a>
        </nav>
    </header>
    <h2>Process a Sale</h2>
    <form method="POST" action="process_sale.php">
        <!-- Customer Info -->
        <input type="text" name="customer_name" placeholder="Customer Name" required>
        <input type="text" name="contact" placeholder="Customer Contact" required>

        <!-- Product Selection -->
        <label for="product_id">Select Product:</label>
        <select name="product_id" id="product_id" required>
            <option value="" disabled selected>Select a product</option>
            <?php if ($products && $products->num_rows > 0): ?>
                <?php while ($row = $products->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['id']) ?>">
                        <?= htmlspecialchars($row['name']) ?> (Stock: <?= htmlspecialchars($row['stock']) ?>)
                    </option>
                <?php endwhile; ?>
            <?php else: ?>
                <option disabled>No products found</option>
            <?php endif; ?>
        </select>

        <!-- Quantity -->
        <input type="number" name="quantity" placeholder="Quantity" min="1" required>

        <!-- Payment Type -->
        <label for="payment_type">Payment Method:</label>
        <select name="payment_type" id="payment_type" required onchange="toggleInstallment(this.value)">
            <option value="cash">Cash</option>
            <option value="installment">Installment</option>
        </select>

        <!-- Installment Info -->
        <div id="installment_section" style="display:none;">
            <input type="number" name="months" placeholder="Number of Months" min="1">
        </div>

        <button type="submit">Complete Sale</button>
    </form>

    <script>
        function toggleInstallment(type) {
            document.getElementById('installment_section').style.display =
                type === 'installment' ? 'block' : 'none';
        }

        // Run this on page load too, in case it's returning from form validation
        window.onload = function() {
            const paymentType = document.getElementById('payment_type').value;
            toggleInstallment(paymentType);
        };
    </script>
</body>
</html>
