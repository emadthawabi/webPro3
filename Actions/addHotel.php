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

    try {
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        $stmt = $db->prepare("INSERT INTO hotels (hotelname, destid, price, stars, time, numofpeople, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sidisis", $hotelname, $destid, $price, $stars, $time, $numofpeople, $location);

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
                    'location' => $location
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
