<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST["customer_id"];
    $end_date = date("Y-m-d");

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update the contract status to terminated
        $sql = "UPDATE Contracts SET  end_date='$end_date' WHERE customer_id='$customer_id'";
        if ($conn->query($sql) !== TRUE) {
            throw new Exception("Error updating contract record: " . $conn->error);
        }

        // Retrieve bill IDs related to the customer
        $bill_ids = [];
        $bill_sql = "SELECT bill_id FROM Bills WHERE customer_id='$customer_id'";
        $bill_result = $conn->query($bill_sql);
        if ($bill_result->num_rows > 0) {
            while ($row = $bill_result->fetch_assoc()) {
                $bill_ids[] = $row['bill_id'];
            }
        }

        // Delete related payments
        if (!empty($bill_ids)) {
            $bill_ids_str = implode(',', $bill_ids);
            $delete_payments_sql = "DELETE FROM Payments WHERE bill_id IN ($bill_ids_str)";
            if ($conn->query($delete_payments_sql) !== TRUE) {
                throw new Exception("Error deleting records from Payments: " . $conn->error);
            }
        }

        // Retrieve and delete all related records from other tables
        $tables = ['Bills', 'MeterReadings', 'UsageEstimation', 'Complaints', 'Notification', 'Meters', 'ServiceRequest', 'Contracts'];
        foreach ($tables as $table) {
            $delete_sql = "DELETE FROM $table WHERE customer_id='$customer_id'";
            if ($conn->query($delete_sql) !== TRUE) {
                throw new Exception("Error deleting records from $table: " . $conn->error);
            }
        }

        // Finally, delete the customer record
        $delete_customer_sql = "DELETE FROM Customers WHERE customer_id='$customer_id'";
        if ($conn->query($delete_customer_sql) !== TRUE) {
            throw new Exception("Error deleting customer record: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();

        echo "Customer terminated and all previous records deleted successfully.";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo $e->getMessage();
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminate Customer</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Terminate Customer</h1>
    <form method="POST" action="terminate_customer.php">
        <label for="customer_id">Customer ID:</label>
        <input type="text" id="customer_id" name="customer_id" required>
        <button type="submit">Terminate Customer</button>
    </form>
    <main><p><a href="admin_dashboard.php"> Back to dashboard</a></p></main>
    
</body>
</html>
