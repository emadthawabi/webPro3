<?php
// Check if the request is a POST request and if the destination ID was provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['destid']) && !empty($_POST['destid'])) {
    // Get the destination ID from the POST data
    $destId = $_POST['destid'];

    try {
        // Connect to the database
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        // Check connection
        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        // Prepare the delete query with a prepared statement to prevent SQL injection
        $stmt = $db->prepare("DELETE FROM destination WHERE destid = ?");
        $stmt->bind_param("i", $destId); // "i" means the parameter is an integer

        // Execute the query
        if ($stmt->execute()) {
            // Success - return a 200 OK status
            http_response_code(200);
            echo "Destination deleted successfully";
        } else {
            // Error executing the query
            throw new Exception("Error deleting destination: " . $stmt->error);
        }

        // Close the statement and database connection
        $stmt->close();
        $db->close();

    } catch (Exception $e) {
        // Return an error status code and message
        http_response_code(500);
        echo "Error: " . $e->getMessage();
    }
} else {
    // Return a bad request status if the destination ID wasn't provided
    http_response_code(400);
    echo "Error: Destination ID is required";
}
?>