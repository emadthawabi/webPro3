<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['flightid']) && !empty($_POST['flightid'])) {
    $flightid = $_POST['flightid'];

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

    $stmt = $conn->prepare("DELETE FROM flights WHERE flightid = ?");
    $stmt->bind_param("i", $flightid);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Failed to delete flight.";
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo "Invalid request.";
}
?>
