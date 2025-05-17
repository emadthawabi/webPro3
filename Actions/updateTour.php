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

    try {
        // Connect to database
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        // Check connection
        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        // Use prepared statement to prevent SQL injection
        $stmt = $db->prepare("UPDATE tours SET tourname = ?, destid = ?, flightid = ?, hotelid = ?, price = ?, rating = ?, duration = ? WHERE tourid = ?");
        $stmt->bind_param("siiiidii", $tourname, $destid, $flightid, $hotelid, $price, $rating, $duration, $tourid);

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
                    'duration' => $duration
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