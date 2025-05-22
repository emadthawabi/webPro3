<?php
// Check if customerid is set
if (isset($_POST['customerid']) && !empty($_POST['customerid'])) {
    $customerId = $_POST['customerid'];

    try {
        // Connect to database
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        // Prepare delete statement
        $stmt = $db->prepare("DELETE FROM customer WHERE customerid = ?");
        $stmt->bind_param("i", $customerId);

        // Execute the query
        if ($stmt->execute()) {
            echo "Customer deleted successfully";
        } else {
            http_response_code(500);
            echo "Error deleting customer: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $db->close();
        header("Location:../admin.php ");
    } catch (Exception $e) {
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "Customer ID is required";
}
?>