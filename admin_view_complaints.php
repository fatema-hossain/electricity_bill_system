<?php
include 'db.php';

$sql = "SELECT * FROM Complaints WHERE status='pending'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Complaint ID</th>
                <th>Customer ID</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['complaint_id']}</td>
                <td>{$row['customer_id']}</td>
                <td>{$row['description']}</td>
                <td>{$row['status']}</td>
                <td>
                    <form method='POST' action='admin_process_complaint.php'>
                        <input type='hidden' name='complaint_id' value='{$row['complaint_id']}'>
                        <button type='submit'>Process</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No pending complaints.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Complaints</title>
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
        button {
            padding: 10px 15px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #34495e;
        }
        p {
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <h1>Pending Complaints</h1>
    <!-- PHP generated table will be inserted here -->
</body>
</html>
<p><a href="admin_dashboard.php"> Back to dashboard</a></p>