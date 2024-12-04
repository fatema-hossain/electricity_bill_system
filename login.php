<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if user is admin or customer
    $sql_admin = "SELECT * FROM Admin WHERE email='$email'";
    $result_admin = $conn->query($sql_admin);

    $sql_customer = "SELECT * FROM Customers WHERE email='$email'";
    $result_customer = $conn->query($sql_customer);

    if ($result_admin->num_rows > 0) {
        $user = $result_admin->fetch_assoc();
        if (password_verify($password, $user['password'])) { // Verify the password
            $_SESSION['user_id'] = $user['admin_id'];
            $_SESSION['user_role'] = 'admin';
            echo "Login successful. Redirecting to dashboard...";
            header('Location: admin_dashboard.php');
            exit();
        } else {
            echo "Invalid password for admin.";
        }
    } elseif ($result_customer->num_rows > 0) {
        $user = $result_customer->fetch_assoc();
        if (password_verify($password, $user['password'])) { // Verify the password
            $_SESSION['user_id'] = $user['customer_id'];
            $_SESSION['user_role'] = 'customer';
            $_SESSION['customer_id'] = $user['customer_id']; // Set customer_id in session
            header('Location: customer_dashboard.php');
            exit();
        } else {
            echo "Invalid password for customer.";
        }
    } else {
        echo "No user found with this email.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Electricity Bill Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        main {
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            width: 100%;
            max-width: 400px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"], input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        button {
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #555;
        }
        p {
            text-align: center;
        }
        p a {
            color: #333;
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
        }
        footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1> Electricity Bill Management System </h1>
    </header>
    <main>
        <h2> Login </h2>
        <section id="login-section">
            <form method="POST" action="login.php" id="login-form">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
        </section>
    </main>
    <footer>
        
    </footer>
    <script src="scripts.js"></script>
</body>
</html>
