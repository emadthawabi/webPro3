<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['airport'], $_POST['time'], $_POST['begin'], $_POST['destid'], $_POST['price'], $_POST['type'], $_POST['date']) &&
    !empty($_POST['airport']) && !empty($_POST['time']) && !empty($_POST['begin']) && !empty($_POST['destid']) &&
    !empty($_POST['price']) && !empty($_POST['type']) && !empty($_POST['date'])) {

    $airport = $_POST['airport'];
    $time = $_POST['time'];
    $begin = $_POST['begin'];
    $destid = $_POST['destid'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $date = $_POST['date'];

    try {
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        if ($db->connect_error) {
            throw new Exception("Database connection failed: " . $db->connect_error);
        }

        $stmt = $db->prepare("INSERT INTO flights (airport, time, begin, destid, price, type, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisss", $airport, $time, $begin, $destid, $price, $type, $date);

        if ($stmt->execute()) {
            $newFlightId = $db->insert_id;

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Flight added successfully',
                'newFlight' => [
                    'flightid' => $newFlightId,
                    'airport' => $airport,
                    'time' => $time,
                    'begin' => $begin,
                    'destid' => $destid,
                    'price' => $price,
                    'type' => $type,
                    'date' => $date
                ]
            ]);
        } else {
            throw new Exception("Error adding flight: " . $stmt->error);
        }

        $stmt->close();
        $db->close();
        header("Location: ../admin.php");

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
