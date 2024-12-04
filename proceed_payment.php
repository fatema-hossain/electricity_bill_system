<?php
include 'db.php';

$bill_id = $_GET["bill_id"];
$payment_success = isset($_GET["success"]) && $_GET["success"] === 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceed to Payment</title>
</head>
<body>
    <h1>Proceed to Payment</h1>

    <?php if ($payment_success): ?>
        <div id="success-message">
            Payment successful! <a href="customer_dashboard.php">Back to Dashboard</a>
        </div>
    <?php else: ?>
        <form method="POST" action="process_payment.php">
            <input type="hidden" name="bill_id" value="<?php echo $bill_id; ?>">
            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method">
                <option value="cash">Cash</option>
                <option value="online">Online</option>
                <option value="credit">Credit</option>
                <option value="debit">Debit</option>
            </select>
            <button type="submit">Pay Now</button>
        </form>
    <?php endif; ?>

</body>
</html>
