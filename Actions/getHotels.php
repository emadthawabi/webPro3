<?php
if (isset($_GET['destid'])) {
    $destid = intval($_GET['destid']);
    $db = new mysqli("localhost", "root", "", "pathfinder", 3306);
    $query = "SELECT hotelid, hotelname, stars FROM hotels WHERE destid = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $destid);
    $stmt->execute();
    $result = $stmt->get_result();
    $hotels = [];

    while ($row = $result->fetch_assoc()) {
        $hotels[] = $row;
    }

    echo json_encode($hotels);
    $stmt->close();
    $db->close();
}
?>
