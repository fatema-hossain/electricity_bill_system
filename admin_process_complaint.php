<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST["complaint_id"];

    $sql = "UPDATE Complaints SET status='resolved' WHERE complaint_id='$complaint_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Complaint processed successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>
<form method="POST" action="admin_process_complaint.php">
<p><a href="admin_dashboard.php"> Back to dashboard</a></p>
</form>
</form>
