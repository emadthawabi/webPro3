<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['hotelname'], $_POST['destid'], $_POST['price'], $_POST['stars'], $_POST['time'], $_POST['numofpeople'], $_POST['location']) &&
    !empty($_POST['hotelname']) && !empty($_POST['destid']) && !empty($_POST['price']) && !empty($_POST['stars']) &&
    !empty($_POST['time']) && !empty($_POST['numofpeople']) && !empty($_POST['location'])) {

    $hotelname = $_POST['hotelname'];
    $destid = $_POST['destid'];
    $price = $_POST['price'];
    $stars = $_POST['stars'];
    $time = $_POST['time'];
    $numofpeople = $_POST['numofpeople'];
    $location = $_POST['location'];
    $image_path = ''; // Default empty path

    try {
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        // Check if an image was uploaded
        if (isset($_FILES['hotel_image']) && $_FILES['hotel_image']['error'] === UPLOAD_ERR_OK) {
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['hotel_image']['type'];

            if (!in_array($file_type, $allowed_types)) {
                throw new Exception("Invalid file type. Only JPG, PNG, and GIF images are allowed.");
            }

            // Validate file size (limit to 5MB)
            if ($_FILES['hotel_image']['size'] > 5242880) {
                throw new Exception("File is too large. Maximum size is 5MB.");
            }

            // Create upload directory if it doesn't exist
            $upload_dir = '../uploadImages/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate unique filename
            $file_extension = pathinfo($_FILES['hotel_image']['name'], PATHINFO_EXTENSION);
            $filename = 'hotel_' . uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $filename;

            // Move uploaded file
            if (move_uploaded_file($_FILES['hotel_image']['tmp_name'], $upload_path)) {
                // If successful, set image path to just the filename
                $image_path = $filename;
            } else {
                throw new Exception("Failed to upload image.");
            }
        }

        $stmt = $db->prepare("INSERT INTO hotels (hotelname, destid, price, stars, time, numofpeople, location, hotelimage) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisisiss", $hotelname, $destid, $price, $stars, $time, $numofpeople, $location, $image_path);

        if ($stmt->execute()) {
            $newHotelId = $db->insert_id;

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Hotel added successfully',
                'newHotel' => [
                    'hotelid' => $newHotelId,
                    'hotelname' => $hotelname,
                    'destid' => $destid,
                    'price' => $price,
                    'stars' => $stars,
                    'time' => $time,
                    'numofpeople' => $numofpeople,
                    'location' => $location,
                    'hotelimage' => $image_path
                ]
            ]);
        } else {
            throw new Exception("Error adding hotel: " . $stmt->error);
        }

        $stmt->close();
        $db->close();
        header("Location:../admin.php ");

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }

} else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Error: All fields are required'
    ]);
}
?>