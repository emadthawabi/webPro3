<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['tourid']) && !empty($_GET['tourid'])) {
    $tourid = $_GET['tourid'];

    try {
        // Connect to database
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        // Check connection
        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        // Use prepared statement to prevent SQL injection
        $stmt = $db->prepare("SELECT * FROM tours WHERE tourid = ?");
        $stmt->bind_param("i", $tourid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $tour = $result->fetch_assoc();

            // Return tour data as JSON
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'tour' => $tour
            ]);
        } else {
            throw new Exception("Tour not found");
        }

        // Close statement and connection
        $stmt->close();
        $db->close();

    } catch (Exception $e) {
        // Return error status and message as JSON
        http_response_code(404);
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