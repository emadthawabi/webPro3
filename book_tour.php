<?php
// book_tour.php
session_start();

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode([
        'success' => false,
        'message' => 'not_logged_in',
        'redirect' => 'login.php'
    ]);
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pathfinder";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Connection failed: ' . $conn->connect_error
    ]);
    exit;
}

// Get the customer ID
$customerId = $_SESSION['customerid'];

// Get tour details from POST request
if (!isset($_POST['tour_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Tour ID is required'
    ]);
    exit;
}

$tourId = intval($_POST['tour_id']);

// Get the tour, flight, hotel, and destination IDs
$sql = "SELECT t.tourid, t.flightid, t.hotelid, t.destid 
        FROM tours t 
        WHERE t.tourid = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tourId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Tour not found'
    ]);
    exit;
}

$tourData = $result->fetch_assoc();

// Get the current customer information
$customerSql = "SELECT ssn, username, password, email, gender, bdate, visanum 
                FROM customer 
                WHERE customerid = ?";
$customerStmt = $conn->prepare($customerSql);
$customerStmt->bind_param("i", $customerId);
$customerStmt->execute();
$customerResult = $customerStmt->get_result();

if ($customerResult->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Customer not found'
    ]);
    exit;
}

$customerData = $customerResult->fetch_assoc();

// Insert a new row in the customer table with the same customer info but new tour details
$insertSql = "INSERT INTO customer (ssn, username, password, email, gender, bdate, tourid, flightid, hotelid, destid, visanum) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param(
    "ssssssiiiis",
    $customerData['ssn'],
    $customerData['username'],
    $customerData['password'],
    $customerData['email'],
    $customerData['gender'],
    $customerData['bdate'],
    $tourData['tourid'],
    $tourData['flightid'],
    $tourData['hotelid'],
    $tourData['destid'],
    $customerData['visanum']
);

if ($insertStmt->execute()) {
    // Get the new booking ID (customer ID)
    $newBookingId = $conn->insert_id;

    echo json_encode([
        'success' => true,
        'message' => 'Tour booked successfully',
        'data' => [
            'booking_id' => $newBookingId,
            'tour_id' => $tourData['tourid'],
            'flight_id' => $tourData['flightid'],
            'hotel_id' => $tourData['hotelid'],
            'dest_id' => $tourData['destid']
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to book tour: ' . $insertStmt->error
    ]);
}

// Close connections
$stmt->close();
$customerStmt->close();
$insertStmt->close();
$conn->close();