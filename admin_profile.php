<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'db.php';

$admin_id = $_SESSION['user_id'];
$query = "SELECT * FROM Admin WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    echo "Admin not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? $admin['name'];
    $email = $_POST['email'] ?? $admin['email'];

    // Password handling
    if (!empty($_POST['current_password']) && !empty($_POST['new_password'])) {
        if (password_verify($_POST['current_password'], $admin['password'])) {
            $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        } else {
            echo "<div class='error-message'>Current password is incorrect.</div>";
            $password = $admin['password']; // retain the old password if current password is incorrect
        }
    } else {
        $password = $admin['password']; // retain the old password if no new password is provided
    }

    $update_query = "UPDATE Admin SET name=?, email=?, password=? WHERE admin_id=?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssi", $name, $email, $password, $admin_id);
    if ($update_stmt->execute()) {
        echo "<div class='success-message'>Profile updated successfully.</div>";
        // Update admin information for display
        $admin['name'] = $name;
        $admin['email'] = $email;
        $admin['password'] = $password;
    } else {
        echo "<div class='error-message'>Error updating profile.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: ;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        main {
            width: 60%;
            margin: 30px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        #profile h2 {
            color: #007BFF;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
        }

        form label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
        }

        form input[type="text"], 
        form input[type="email"], 
        form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        form input[type="submit"] {
            margin-top: 20px;
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .success-message, .error-message {
            text-align: center;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }

       
    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="profile">
            <h2>Your Profile</h2>
            <form action="" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($admin['name']); ?>">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>">

                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password">

                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password">

                <input type="submit" value="Update Profile">
            </form>
        </section>
    </main>
   
    <script src="scripts.js"></script>
</body>
</html>
