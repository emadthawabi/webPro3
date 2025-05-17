<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pathfinder";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Added logging to troubleshoot the issue
error_log("Update Hotel Request: " . print_r($_POST, true));

if (
    isset($_POST['hotelid'], $_POST['destid'], $_POST['price'], $_POST['stars'],
        $_POST['time'], $_POST['numofpeople'], $_POST['location'])
) {
    // Log the location value specifically
    error_log("Location value: " . $_POST['location']);

    $stmt = $conn->prepare("UPDATE hotels SET destid=?, price=?, stars=?, time=?, numofpeople=?, location=? WHERE hotelid=?");

    // Make sure types match the database field types
    $stmt->bind_param("idisisi",
        $_POST['destid'],
        $_POST['price'],
        $_POST['stars'],
        $_POST['time'],
        $_POST['numofpeople'],
        $_POST['location'],
        $_POST['hotelid']
    );

    if ($stmt->execute()) {
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            error_log("Update successful, affected rows: " . $stmt->affected_rows);
            echo "success";
        } else {
            error_log("No rows updated. SQL error: " . $stmt->error);
            echo "no_change";
        }
    } else {
        error_log("Update failed. SQL error: " . $stmt->error);
        echo "error";
    }

    $stmt->close();
} else {
    error_log("Invalid request, missing required fields");
    echo "invalid";
}
$conn->close();
?>