<?php

// Set headers for JSON response
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Debug: Log received POST data
error_log("POST data received: " . print_r($_POST, true));

// Validate required fields
$required_fields = ['destid', 'continent', 'country', 'city', 'description'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === '') {
        echo json_encode(['success' => false, 'message' => "Field '$field' is required or empty"]);
        error_log("Validation failed for field: $field - Value: " . (isset($_POST[$field]) ? $_POST[$field] : 'not set'));
        exit;
    }
}

// Sanitize input
$destid = intval($_POST['destid']);
$continent = htmlspecialchars(trim($_POST['continent']));
$country = htmlspecialchars(trim($_POST['country']));
$city = htmlspecialchars(trim($_POST['city']));
$description = htmlspecialchars(trim($_POST['description']));

// Additional validation for destination ID
if ($destid <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid destination ID']);
    exit;
}

try {
    // Connect to database
    $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

    // Check connection
    if ($db->connect_error) {
        throw new Exception("Connection failed: " . $db->connect_error);
    }

    // Prepare and execute the update statement
    $stmt = $db->prepare("UPDATE destination SET continent = ?, country = ?, city = ?, description = ? WHERE destid = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $db->error);
    }

    $stmt->bind_param("ssssi", $continent, $country, $city, $description, $destid);

    if (!$stmt->execute()) {
        throw new Exception("Error updating record: " . $stmt->error);
    }

    // Check if any rows were affected
    if ($stmt->affected_rows === 0) {
        // Check if the destination exists
        $checkStmt = $db->prepare("SELECT destid FROM destination WHERE destid = ?");
        $checkStmt->bind_param("i", $destid);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Destination not found']);
            $checkStmt->close();
            exit;
        }

        $checkStmt->close();

        // If destination exists but no changes made
        echo json_encode(['success' => true, 'message' => 'No changes made to destination']);
        exit;
    }

    // Close statement
    $stmt->close();
    header("Location: ../admin.php");

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
    error_log("Error in updateDestination.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    // Close database connection if it exists
    if (isset($db) && $db instanceof mysqli) {
        $db->close();
    }
}