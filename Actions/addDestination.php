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
    $destimage = ''; // Default empty string for image filename

    try {
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

            // Move uploaded file
            if(move_uploaded_file($_FILES['destimage']['tmp_name'], $upload_path)) {
                // Store only the filename in the database
                $destimage = $filename;
            } else {
                throw new Exception("Error uploading file");
            }
        }

        // Connect to database
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        // Check connection
        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        // Update the SQL query to include the destimage column
        $stmt = $db->prepare("INSERT INTO destination (continent, country, city, description, destimage) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $continent, $country, $city, $description, $destimage);

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
                    'description' => $description,
                    'destimage' => $destimage
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