<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['continent']) && isset($_POST['country']) &&
    isset($_POST['city']) && isset($_POST['description']) &&
    !empty($_POST['continent']) && !empty($_POST['country']) &&
    !empty($_POST['city'])) {

    $continent = $_POST['continent'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $description = $_POST['description'];

    try {
        // Connect to database
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        // Check connection
        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        // Use prepared statement to prevent SQL injection
        $stmt = $db->prepare("INSERT INTO destination (continent, country, city, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $continent, $country, $city, $description);

        if ($stmt->execute()) {
            // Get the newly inserted ID
            $newDestId = $db->insert_id;

            // Return success message as JSON
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Destination added successfully',
                'newDestination' => [
                    'destid' => $newDestId,
                    'continent' => $continent,
                    'country' => $country,
                    'city' => $city,
                    'description' => $description
                ]
            ]);
        } else {
            throw new Exception("Error adding destination: " . $stmt->error);
        }

        // Close statement and connection
        $stmt->close();
        $db->close();
        header("Location: ../admin.php");

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
        'message' => 'Error: All fields are required'
    ]);
}
?>