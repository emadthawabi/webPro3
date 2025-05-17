<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['flightid'], $_POST['airport'], $_POST['begin'], $_POST['destid'], $_POST['price'], $_POST['type'], $_POST['date'], $_POST['time']) &&
    !empty($_POST['flightid']) && !empty($_POST['airport']) && !empty($_POST['begin']) &&
    !empty($_POST['destid']) && !empty($_POST['price']) && !empty($_POST['type']) &&
    !empty($_POST['date']) && !empty($_POST['time'])) {

    $flightid = $_POST['flightid'];
    $airport = $_POST['airport'];
    $begin = $_POST['begin'];
    $destid = $_POST['destid'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pathfinder";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        http_response_code(500);
        echo "Database connection failed: " . $conn->connect_error;
        exit;
    }

    $stmt = $conn->prepare("UPDATE flights SET airport = ?, begin = ?, destid = ?, price = ?, type = ?, date = ?, time = ? WHERE flightid = ?");
    $stmt->bind_param("ssissssi", $airport, $begin, $destid, $price, $type, $date, $time, $flightid);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error updating flight: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    http_response_code(400);
    echo "Invalid request. All fields are required.";
}
?>
