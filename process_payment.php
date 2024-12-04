<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bill_id = $_POST["bill_id"];
    $payment_method = $_POST["payment_method"];
    $payment_date = date("Y-m-d");

    // Update bill status to paid
    $sql = "UPDATE Bills SET status='paid' WHERE bill_id='$bill_id'";
    if ($conn->query($sql) === TRUE) {
        // Insert payment record
        $payment_sql = "INSERT INTO Payments (bill_id, payment_date, amount) SELECT bill_id, '$payment_date', amount FROM Bills WHERE bill_id='$bill_id'";
        if ($conn->query($payment_sql) === TRUE) {
            echo "Payment successful!";
        } else {
            echo "Error: " . $payment_sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
