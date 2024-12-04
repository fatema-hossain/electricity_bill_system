<?php
include 'db.php';

// Fetch all customers' details along with their meter IDs
$customers_query = "SELECT C.customer_id, C.name, M.meter_id FROM Customers C JOIN Meters M ON C.customer_id = M.customer_id";
$customers_result = $conn->query($customers_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST["customer_id"];
    $meter_id = $_POST["meter_id"];
    $reading_value = $_POST["reading_value"];
    $reading_date = date("Y-m-d"); // Capture the current date as the reading date

    // Insert meter reading
    $sql = "INSERT INTO MeterReadings (customer_id, meter_id, reading_date, reading_value) VALUES ('$customer_id', '$meter_id', '$reading_date', '$reading_value')";
    if ($conn->query($sql) === TRUE) {
        echo "Reading inserted successfully<br>";
    } else {
        echo "Error inserting reading: " . $sql . "<br>" . $conn->error;
        exit();
    }

    // Retrieve the area rate based on customer area_id
    $rate_sql = "SELECT A.rate FROM Area A JOIN Customers C ON A.area_id = C.area_id WHERE C.customer_id = '$customer_id'";
    $rate_result = $conn->query($rate_sql);
    if ($rate_result->num_rows > 0) {
        $rate_row = $rate_result->fetch_assoc();
        $rate = $rate_row['rate'];
    } else {
        echo "Error retrieving rate<br>";
        exit();
    }

    // Calculate bill amount based on area rate
    $amount = $reading_value * $rate;
    $due_date = date("Y-m-d", strtotime("+30 days"));

    // Insert bill
    $bill_sql = "INSERT INTO Bills (customer_id, amount, due_date, status) VALUES ('$customer_id', '$amount', '$due_date', 'unpaid')";
    if ($conn->query($bill_sql) === TRUE) {
        echo "Bill generated successfully<br>";
    } else {
        echo "Error inserting bill: " . $bill_sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Reading</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        form {
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            width: 100%;
            max-width: 500px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        button {
            width: 100%;
            padding: 10px;
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
        
        p  {
            width:10%;
            padding: 12px;
            background-color: white;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;

        }
        p :hover {
            background-color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Insert Reading</h1>
    </header>

    <form method="POST" action="insert_reading.php">
        <label for="customer_id">Customer:</label>
        <select id="customer_id" name="customer_id" required>
            <?php
            if ($customers_result->num_rows > 0) {
                while($row = $customers_result->fetch_assoc()) {
                    echo "<option value='".$row['customer_id']."' data-meter-id='".$row['meter_id']."'>".$row['name']." (ID: ".$row['customer_id'].", Meter ID: ".$row['meter_id'].")</option>";
                }
            }
            ?>
        </select>
        <label for="meter_id">Meter ID:</label>
        <input type="text" id="meter_id" name="meter_id" required>
        <label for="reading_value">Reading Value:</label>
        <input type="text" id="reading_value" name="reading_value" required>
        <button type="submit">Insert Reading</button>
        

    </form>
   <p><a href="admin_dashboard.php"> Back to dashboard</a></p>
    <script>
        // JavaScript to auto-fill meter ID based on selected customer
        document.getElementById('customer_id').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            document.getElementById('meter_id').value = selectedOption.getAttribute('data-meter-id');
        });
    </script>
</body>
</html>
