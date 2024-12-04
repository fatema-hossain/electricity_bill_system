<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $area_id = $_POST["area_id"];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert the customer details into the Customers table
        $sql = "INSERT INTO Customers (name, email, password, address, phone, area_id) 
                VALUES ('$name', '$email', '$password', '$address', '$phone', '$area_id')";
        if ($conn->query($sql) !== TRUE) {
            throw new Exception("Error inserting customer: " . $conn->error);
        }

        // Get the last inserted customer_id
        $customer_id = $conn->insert_id;

        // Set the customer_id in the session
        $_SESSION['customer_id'] = $customer_id;

        // Insert into the Contracts table
        $contract_start_date = date("Y-m-d");
        $contract_end_date = date("Y-m-d", strtotime("+1 year"));
        $contract_sql = "INSERT INTO Contracts (customer_id, start_date, end_date) 
                         VALUES ('$customer_id', '$contract_start_date', '$contract_end_date')";
        if ($conn->query($contract_sql) !== TRUE) {
            throw new Exception("Error inserting contract: " . $conn->error);
        }

        // Insert into the Meters table
        $installation_date = date("Y-m-d");
        $meter_sql = "INSERT INTO Meters (customer_id, installation_date) 
                      VALUES ('$customer_id', '$installation_date')";
        if ($conn->query($meter_sql) !== TRUE) {
            throw new Exception("Error inserting meter: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();

        echo "Signup successful. You can now <a href='login.php'>login</a>.";
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Electricity Bill Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 20px;
    flex-direction: column;
        }
    </style>

</head>
<body>
    <header>
        <h1>Sign Up for Electricity Bill Management System</h1>
    </header>
    <main>
        <section id="signup-section">
            <form method="POST" action="signup.php" id="signup-form">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>

                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>

                <label for="area_id">Area ID:</label>
                <select id="area_id" name="area_id" required>
                    <option value="1">City</option>
                    <option value="2">Rural</option>
                </select>

                <button type="submit">Sign Up</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Electricity Bill Management System. All rights reserved.</p>
    </footer>
    <script src="scripts.js"></script>
</body>
</html>
