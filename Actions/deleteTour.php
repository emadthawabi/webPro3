<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tourid']) && !empty($_POST['tourid'])) {
    $tourid = $_POST['tourid'];

    try {
        // Connect to database
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        // Check connection
        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        // Use prepared statement to prevent SQL injection
        $stmt = $db->prepare("DELETE FROM tours WHERE tourid = ?");
        $stmt->bind_param("i", $tourid);

        if ($stmt->execute()) {
            // Return success message as JSON
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Tour deleted successfully'
            ]);
        } else {
            throw new Exception("Error deleting tour: " . $stmt->error);
        }

        // Close statement and connection
        $stmt->close();
        $db->close();

    } catch (Exception $e) {
        // Return error status and message as JSON
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    // Return bad request status if required fields are missing
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Error: Tour ID is required'
    ]);
}
?>