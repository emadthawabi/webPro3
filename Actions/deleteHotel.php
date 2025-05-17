<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pathfinder";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hotelid'])) {
    $hotelid = intval($_POST['hotelid']);

    $stmt = $conn->prepare("DELETE FROM hotels WHERE hotelid = ?");
    $stmt->bind_param("i", $hotelid);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
} else {
    echo "invalid";
}

$conn->close();
?>
