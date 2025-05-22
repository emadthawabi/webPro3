<?php
if (isset($_GET['destid'])) {
    $destid = intval($_GET['destid']);
    $db = new mysqli("localhost", "root", "", "pathfinder", 3306);
    $query = "SELECT flightid, airport, begin FROM flights WHERE destid = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $destid);
    $stmt->execute();
    $result = $stmt->get_result();
    $flights = [];

    while ($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }

    echo json_encode($flights);
    $stmt->close();
    $db->close();
}
?>
