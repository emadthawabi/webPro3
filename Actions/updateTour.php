<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['tourid'], $_POST['tourname'], $_POST['destid'], $_POST['flightid'],
        $_POST['hotelid'], $_POST['price'], $_POST['rating'], $_POST['duration']) &&
    !empty($_POST['tourid'])) {

    $tourid = $_POST['tourid'];
    $tourname = $_POST['tourname'];
    $destid = $_POST['destid'];
    $flightid = $_POST['flightid'];
    $hotelid = $_POST['hotelid'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $duration = $_POST['duration'];

    // Get the current image path if provided
    $current_image = isset($_POST['current_image']) ? $_POST['current_image'] : '';
    $image_path = $current_image; // Default to keeping current image

    try {
        // Connect to database
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        // Check connection
        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        // Check if a new image was uploaded
        if (isset($_FILES['tour_image']) && $_FILES['tour_image']['error'] === UPLOAD_ERR_OK) {
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['tour_image']['type'];

            if (!in_array($file_type, $allowed_types)) {
                throw new Exception("Invalid file type. Only JPG, PNG, and GIF images are allowed.");
            }

            // Validate file size (limit to 5MB)
            if ($_FILES['tour_image']['size'] > 5242880) {
                throw new Exception("File is too large. Maximum size is 5MB.");
            }

            // Create upload directory if it doesn't exist
            $upload_dir = '../uploadImages/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate unique filename
            $file_extension = pathinfo($_FILES['tour_image']['name'], PATHINFO_EXTENSION);
            $filename = 'tour_' . uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $filename;

            // Move uploaded file
            if (move_uploaded_file($_FILES['tour_image']['tmp_name'], $upload_path)) {
                // If successful, store only the filename in the database
                $image_path = $filename;

                // Delete old image if it exists and is not empty
                if (!empty($current_image) && file_exists($upload_dir . $current_image)) {
                    unlink($upload_dir . $current_image);
                }
            } else {
                throw new Exception("Failed to upload image.");
            }
        }

        // Use prepared statement to prevent SQL injection
        $stmt = $db->prepare("UPDATE tours SET tourname = ?, destid = ?, flightid = ?, hotelid = ?, price = ?, rating = ?, duration = ?, image = ? WHERE tourid = ?");
        $stmt->bind_param("siiiidisi", $tourname, $destid, $flightid, $hotelid, $price, $rating, $duration, $image_path, $tourid);

        if ($stmt->execute()) {
            // Return success message as JSON
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Tour updated successfully',
                'updatedTour' => [
                    'tourid' => $tourid,
                    'tourname' => $tourname,
                    'destid' => $destid,
                    'flightid' => $flightid,
                    'hotelid' => $hotelid,
                    'price' => $price,
                    'rating' => $rating,
                    'duration' => $duration,
                    'image' => $image_path
                ]
            ]);
        } else {
            throw new Exception("Error updating tour: " . $stmt->error);
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
        'message' => 'Error: All fields are required'
    ]);
}
?>