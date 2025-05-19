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
$destimage = null; // Will be set if a new image is uploaded

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

    // Handle image upload if a file was submitted
    if(isset($_FILES['destimage']) && $_FILES['destimage']['error'] === UPLOAD_ERR_OK) {
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['destimage']['type'];

        if (!in_array($file_type, $allowed_types)) {
            throw new Exception("Invalid file type. Only JPG, PNG, and GIF images are allowed.");
        }

        // Validate file size (limit to 5MB)
        if ($_FILES['destimage']['size'] > 5242880) {
            throw new Exception("File is too large. Maximum size is 5MB.");
        }

        // Create upload directory if it doesn't exist
        $upload_dir = '../uploadImages/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique filename
        $file_extension = pathinfo($_FILES['destimage']['name'], PATHINFO_EXTENSION);
        $filename = 'destination_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $filename;

        // Move the uploaded file to the specified directory
        if(move_uploaded_file($_FILES['destimage']['tmp_name'], $upload_path)) {
            // Store only the filename in the database
            $destimage = $filename;

            // Update including the new image
            $stmt = $db->prepare("UPDATE destination SET continent = ?, country = ?, city = ?, description = ?, destimage = ? WHERE destid = ?");
            $stmt->bind_param("sssssi", $continent, $country, $city, $description, $destimage, $destid);
        } else {
            throw new Exception("Error uploading file");
        }
    } else {
        // Update without changing the image
        $stmt = $db->prepare("UPDATE destination SET continent = ?, country = ?, city = ?, description = ? WHERE destid = ?");
        $stmt->bind_param("ssssi", $continent, $country, $city, $description, $destid);
    }

    if (!$stmt->execute()) {
        throw new Exception("Error updating record: " . $stmt->error);
    }

    // Check if any rows were affected
    if ($stmt->affected_rows === 0) {
        // Check if the destination exists
        $checkStmt = $db->prepare("SELECT destid, destimage FROM destination WHERE destid = ?");
        $checkStmt->bind_param("i", $destid);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Destination not found']);
            $checkStmt->close();
            exit;
        }

        $destination = $checkResult->fetch_assoc();
        $currentImage = $destination['destimage'];
        $checkStmt->close();

        // If destination exists but no changes made
        echo json_encode([
            'success' => true,
            'message' => 'No changes made to destination',
            'destination' => [
                'destid' => $destid,
                'continent' => $continent,
                'country' => $country,
                'city' => $city,
                'description' => $description,
                'destimage' => $currentImage
            ]
        ]);
        exit;
    }

    // Get the current image path if we didn't upload a new one
    if ($destimage === null) {
        $imgStmt = $db->prepare("SELECT destimage FROM destination WHERE destid = ?");
        $imgStmt->bind_param("i", $destid);
        $imgStmt->execute();
        $imgResult = $imgStmt->get_result();

        if ($imgResult->num_rows > 0) {
            $row = $imgResult->fetch_assoc();
            $destimage = $row['destimage'];
        }

        $imgStmt->close();
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
            'description' => $description,
            'destimage' => $destimage
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