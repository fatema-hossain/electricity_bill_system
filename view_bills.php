<?php
session_start();
include 'db.php';

// Check if customer_id is set in the session
if (isset($_SESSION['customer_id'])) {
    $customer_id = $_SESSION['customer_id'];

    $sql = "SELECT * FROM Bills WHERE customer_id='$customer_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Bill ID</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['bill_id']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['due_date']}</td>
                    <td>{$row['status']}</td>
                    <td>";
            if ($row['status'] == 'unpaid') {
                echo "<a href='proceed_payment.php?bill_id={$row['bill_id']}'>Proceed to Payment</a>";
            }
            echo "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No bills found.</p>";
    }
} else {
    echo "<p>Customer ID not found in session.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bills</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        p {
            color: #2c3e50;
        }
    </style>
</head>
        <header>
        <h1>View Bills</h1>
        <p><a href="customer_dashboard.php"> Back to dashboard</a></p>
    </header>
<body>
   
    <!-- PHP generated table will be inserted here -->
</body>
</html>
