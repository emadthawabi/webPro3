<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['tourname'], $_POST['destid'], $_POST['flightid'], $_POST['hotelid'], $_POST['price'], $_POST['rating'], $_POST['duration']) &&
    !empty($_POST['tourname']) && !empty($_POST['destid']) &&
    !empty($_POST['flightid']) && !empty($_POST['hotelid']) &&
    !empty($_POST['price']) && !empty($_POST['rating']) &&
    !empty($_POST['duration'])) {

    $tourname = $_POST['tourname'];
    $destid = $_POST['destid'];
    $flightid = $_POST['flightid'];
    $hotelid = $_POST['hotelid'];
    $price = $_POST['price'];
    $rating = $_POST['rating'];
    $duration = $_POST['duration'];
    $image_path = ''; // Default empty path

    try {
        // Connect to database
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        // Check connection
        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        // Check if an image was uploaded
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
            } else {
                throw new Exception("Failed to upload image.");
            }
        }

        // Use prepared statement to prevent SQL injection
        $stmt = $db->prepare("INSERT INTO tours (tourname, destid, flightid, hotelid, price, rating, duration, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiiidis", $tourname, $destid, $flightid, $hotelid, $price, $rating, $duration, $image_path);

        if ($stmt->execute()) {
            // Get the newly inserted tour ID
            $newTourId = $db->insert_id;

            // Return success message as JSON
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Tour added successfully',
                'newTour' => [
                    'tourid' => $newTourId,
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
            throw new Exception("Error adding tour: " . $stmt->error);
        }

        // Close statement and connection
        $stmt->close();
        $db->close();
        header("Location:../admin.php ");

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