<?php

// Set headers for JSON response
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate required fields
$required_fields = ['destid', 'continent', 'country', 'city', 'description'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Field '$field' is required"]);
        exit;
    }
}

// Sanitize input
$destid = intval($_POST['destid']);
$continent = htmlspecialchars(trim($_POST['continent']));
$country = htmlspecialchars(trim($_POST['country']));
$city = htmlspecialchars(trim($_POST['city']));
$description = htmlspecialchars(trim($_POST['description']));

try {
    // Connect to database
    $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

    // Check connection
    if ($db->connect_error) {
        throw new Exception("Connection failed: " . $db->connect_error);
    }

    // Prepare and execute the update statement
    $stmt = $db->prepare("UPDATE destination SET continent = ?, country = ?, city = ?, description = ? WHERE destid = ?");
    $stmt->bind_param("ssssi", $continent, $country, $city, $description, $destid);

    if (!$stmt->execute()) {
        throw new Exception("Error updating record: " . $stmt->error);
    }

    // Check if any rows were affected
    if ($stmt->affected_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'No changes made or destination not found']);
        exit;
    }

    // Close statement
    $stmt->close();

    // Return success with the updated destination
    echo json_encode([
        'success' => true,
        'message' => 'Destination updated successfully',
        'destination' => [
            'destid' => $destid,
            'continent' => $continent,
            'country' => $country,
            'city' => $city,
            'description' => $description
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // Close database connection if it exists
    if (isset($db) && $db instanceof mysqli) {
        $db->close();
    }
}
