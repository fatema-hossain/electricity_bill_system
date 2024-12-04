<?php
session_start();

// Check if customer_id is set in session
if (!isset($_SESSION['customer_id'])) {
    die("Customer ID is not set in session.");
}

// Get the customer_id from session
$customer_id = $_SESSION['customer_id'];

// Include the database connection file
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the complaint description from POST data
    $description = $_POST["description"];
    $complaint_date = date("Y-m-d"); // Set the complaint date

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare the SQL statement to insert the complaint
        $stmt = $conn->prepare("INSERT INTO Complaints (customer_id, complaint_date, description, status) 
                                VALUES (?, ?, ?, 'Pending')");
        $stmt->bind_param("iss", $customer_id, $complaint_date, $description);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Complaint filed successfully.";
        } else {
            throw new Exception("Error: " . $stmt->error);
        }

        // Commit the transaction
        $conn->commit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File a Complaint - Electricity Bill Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>File a Complaint</h1>
        <nav>
            <ul>
                <li><a href="customer_dashboard.php"> Back to dashboard</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="complaint-section">
            <form method="POST" action="file_complaint.php" id="complaint-form">
                <label for="description">Complaint Description:</label>
                <textarea id="description" name="description" required></textarea>
                <button type="submit">Submit Complaint</button>
            </form>
        </section>
    </main>
    
    
    <footer>
    
    </footer>
    <script src="scripts.js"></script>
</body>
</html>
