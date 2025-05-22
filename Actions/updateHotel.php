<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pathfinder";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Added logging to troubleshoot the issue
error_log("Update Hotel Request: " . print_r($_POST, true));

if (
    isset($_POST['hotelid'], $_POST['hotelname'], $_POST['destid'], $_POST['price'], $_POST['stars'],
        $_POST['time'], $_POST['numofpeople'], $_POST['location'])
) {
    // Log all values for debugging
    error_log("hotelid: " . $_POST['hotelid']);
    error_log("hotelname: " . $_POST['hotelname']);
    error_log("destid: " . $_POST['destid']);
    error_log("price: " . $_POST['price']);
    error_log("stars: " . $_POST['stars']);
    error_log("time: " . $_POST['time']);
    error_log("numofpeople: " . $_POST['numofpeople']);
    error_log("location: " . $_POST['location']);

    $hotelid = $_POST['hotelid'];
    $hotelname = $_POST['hotelname'];
    $destid = (int)$_POST['destid'];
    $price = $_POST['price'];
    $stars = $_POST['stars'];
    $time = $_POST['time'];
    $numofpeople = $_POST['numofpeople']; // This is a VARCHAR in the database
    $location = $_POST['location'];

    // Initialize image path variable
    $image_path = null;
    $update_image = false;

    // Check if an image was uploaded
    if (isset($_FILES['hotel_image']) && $_FILES['hotel_image']['error'] === UPLOAD_ERR_OK && $_FILES['hotel_image']['size'] > 0) {
        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['hotel_image']['type'];

        if (!in_array($file_type, $allowed_types)) {
            error_log("Invalid file type uploaded");
            echo "invalid_file_type";
            exit;
        }

        // Validate file size (limit to 5MB)
        if ($_FILES['hotel_image']['size'] > 5242880) {
            error_log("File too large");
            echo "file_too_large";
            exit;
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
            $update_image = true;

            // Delete old image if exists and is not empty
            if (isset($_POST['current_image']) && !empty($_POST['current_image'])) {
                $old_image_path = $upload_dir . $_POST['current_image'];
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        } else {
            error_log("Failed to upload image");
            echo "image_upload_failed";
            exit;
        }
    }

    try {
        // Prepare the query based on whether we're updating the image or not
        if ($update_image) {
            $stmt = $conn->prepare("UPDATE hotels SET hotelname=?, destid=?, price=?, stars=?, time=?, numofpeople=?, location=?, hotelimage=? WHERE hotelid=?");
            // FIXED: Corrected binding parameters to match column types
            // 's' for strings, 'i' for integers - numofpeople is VARCHAR in DB so should be 's'
            $stmt->bind_param("sissssssi",
                $hotelname,
                $destid,
                $price,
                $stars,
                $time,
                $numofpeople,
                $location,
                $image_path,
                $hotelid
            );
        } else {
            $stmt = $conn->prepare("UPDATE hotels SET hotelname=?, destid=?, price=?, stars=?, time=?, numofpeople=?, location=? WHERE hotelid=?");
            // FIXED: Corrected binding parameters - numofpeople is VARCHAR in DB so should be 's'
            $stmt->bind_param("sisssssi",
                $hotelname,
                $destid,
                $price,
                $stars,
                $time,
                $numofpeople,
                $location,
                $hotelid
            );
        }

        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->affected_rows > 0 || $stmt->affected_rows === 0) {
                error_log("Update successful or no changes needed");
                echo "success";
            } else {
                error_log("No rows updated. SQL error: " . $stmt->error);
                echo "no_change";
            }
        } else {
            error_log("Update failed. SQL error: " . $stmt->error);
            echo "error: " . $stmt->error;
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        echo "error: " . $e->getMessage();
    }

} else {
    error_log("Invalid request, missing required fields");
    echo "invalid";
}
$conn->close();
?>