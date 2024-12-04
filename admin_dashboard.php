<?php
include 'db.php';

$order_by_meters = 'customer_id'; // Default order for customer and meter details
$order_meters = 'ASC'; // Default order direction

$order_by_bills = 'customer_id'; // Default order for bill details
$order_bills = 'ASC'; // Default order direction

$customer_id_filter = '';
$due_date_filter = '';

if (isset($_GET['order_by_meters']) && in_array($_GET['order_by_meters'], ['customer_id', 'meter_id', 'name', 'address'])) {
    $order_by_meters = $_GET['order_by_meters'];
}

if (isset($_GET['order_meters']) && in_array($_GET['order_meters'], ['ASC', 'DESC'])) {
    $order_meters = $_GET['order_meters'];
}

if (isset($_GET['order_by_bills']) && in_array($_GET['order_by_bills'], ['customer_id', 'bill_id', 'due_date', 'name', 'address'])) {
    $order_by_bills = $_GET['order_by_bills'];
}

if (isset($_GET['order_bills']) && in_array($_GET['order_bills'], ['ASC', 'DESC'])) {
    $order_bills = $_GET['order_bills'];
}

if (isset($_GET['customer_id_filter'])) {
    $customer_id_filter = $_GET['customer_id_filter'];
}

if (isset($_GET['due_date_filter'])) {
    $due_date_filter = $_GET['due_date_filter'];
}

// Fetch customer details from Meters table with filtering
$meters_query = "SELECT Meters.customer_id, meter_id, Customers.name, Customers.address 
                 FROM Meters 
                 JOIN Customers ON Meters.customer_id = Customers.customer_id 
                 WHERE Meters.customer_id LIKE '%$customer_id_filter%' 
                 ORDER BY $order_by_meters $order_meters";
$meters_result = $conn->query($meters_query);

// Fetch bill details from Bills table with filtering
$bills_query = "SELECT Bills.customer_id, bill_id, due_date, status, Customers.name, Customers.address 
                FROM Bills 
                JOIN Customers ON Bills.customer_id = Customers.customer_id 
                WHERE Bills.customer_id LIKE '%$customer_id_filter%' AND due_date LIKE '%$due_date_filter%' 
                ORDER BY $order_by_bills $order_bills";
$bills_result = $conn->query($bills_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="insert_reading.php">Insert Reading and Generate Bill</a></li>
                <li><a href="terminate_customer.php">Terminate Customer</a></li>
                <li><a href="admin_view_complaints.php">View Complaints</a></li>
                <li><a href="admin_profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Customer and Meter Details</h2>
            <form method="GET" action="admin_dashboard.php">
                <input type="text" name="customer_id_filter" placeholder="Filter by Customer ID" value="<?php echo htmlspecialchars($customer_id_filter); ?>">
                <button type="submit">Apply Filter</button>
                
                <label for="order_by_meters">Sort by:</label>
                <select name="order_by_meters" id="order_by_meters">
                    <option value="customer_id" <?php echo $order_by_meters == 'customer_id' ? 'selected' : ''; ?>>Customer ID</option>
                    <option value="meter_id" <?php echo $order_by_meters == 'meter_id' ? 'selected' : ''; ?>>Meter ID</option>
                    <option value="name" <?php echo $order_by_meters == 'name' ? 'selected' : ''; ?>>Name</option>
                    <option value="address" <?php echo $order_by_meters == 'address' ? 'selected' : ''; ?>>Address</option>
                </select>

                <label for="order_meters">Order:</label>
                <select name="order_meters" id="order_meters">
                    <option value="ASC" <?php echo $order_meters == 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                    <option value="DESC" <?php echo $order_meters == 'DESC' ? 'selected' : ''; ?>>Descending</option>
                </select>
                <button type="submit">Sort</button>
            </form>
            <table border="1">
                <tr>
                    <th>Customer ID</th>
                    <th>Meter ID</th>
                    <th>Name</th>
                    <th>Address</th>
                </tr>
                <?php
                if ($meters_result->num_rows > 0) {
                    while($row = $meters_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['customer_id'] . "</td>";
                        echo "<td>" . $row['meter_id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No data available</td></tr>";
                }
                ?>
            </table>
        </section>
        <section>
            <h2>Bill Details</h2>
            <form method="GET" action="admin_dashboard.php">
                <input type="text" name="customer_id_filter" placeholder="Filter by Customer ID" value="<?php echo htmlspecialchars($customer_id_filter); ?>">
                <input type="text" name="due_date_filter" placeholder="Filter by Due Date" value="<?php echo htmlspecialchars($due_date_filter); ?>">
                <button type="submit">Apply Filter</button>
                
                <label for="order_by_bills">Sort by:</label>
                <select name="order_by_bills" id="order_by_bills">
                    <option value="customer_id" <?php echo $order_by_bills == 'customer_id' ? 'selected' : ''; ?>>Customer ID</option>
                    <option value="bill_id" <?php echo $order_by_bills == 'bill_id' ? 'selected' : ''; ?>>Bill ID</option>
                    <option value="due_date" <?php echo $order_by_bills == 'due_date' ? 'selected' : ''; ?>>Due Date</option>
                    <option value="name" <?php echo $order_by_bills == 'name' ? 'selected' : ''; ?>>Name</option>
                    <option value="address" <?php echo $order_by_bills == 'address' ? 'selected' : ''; ?>>Address</option>
                </select>

                <label for="order_bills">Order:</label>
                <select name="order_bills" id="order_bills">
                    <option value="ASC" <?php echo $order_bills == 'ASC' ? 'selected' : ''; ?>>Ascending</option>
                    <option value="DESC" <?php echo $order_bills == 'DESC' ? 'selected' : ''; ?>>Descending</option>
                </select>
                <button type="submit">Sort</button>
            </form>
            <table border="1">
                <tr>
                    <th>Customer ID</th>
                    <th>Bill ID</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Name</th>
                    <th>Address</th>
                </tr>
                <?php
                if ($bills_result->num_rows > 0) {
                    while($row = $bills_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['customer_id'] . "</td>";
                        echo "<td>" . $row['bill_id'] . "</td>";
                        echo "<td>" . $row['due_date'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No data available</td></tr>";
                }
                ?>
            </table>
        </section>
    </main>
    <footer>
        <!-- Your footer content -->
    </footer>
    <script src="scripts.js"></script>
</body>
</html>
