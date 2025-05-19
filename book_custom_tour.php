<?php
// book_custom_tour.php - Fixed to prevent double booking
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

// Check if this is a repeated submission (using a token to prevent double submissions)
if (!isset($_POST['booking_token']) || empty($_POST['booking_token'])) {
    $_SESSION['booking_token'] = md5(uniqid(mt_rand(), true));

    echo json_encode([
        'success' => false,
        'message' => 'Missing booking token',
        'booking_token' => $_SESSION['booking_token']
    ]);
    exit;
}

// Verify that the submitted token matches the one in the session
if (!isset($_SESSION['booking_token']) || $_POST['booking_token'] !== $_SESSION['booking_token']) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid booking token',
        'booking_token' => $_SESSION['booking_token'] ?? md5(uniqid(mt_rand(), true))
    ]);
    exit;
}

// Get a fresh token for next submission
$currentToken = $_SESSION['booking_token'];
$_SESSION['booking_token'] = md5(uniqid(mt_rand(), true));

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
        'message' => 'Connection failed: ' . $conn->connect_error,
        'booking_token' => $_SESSION['booking_token']
    ]);
    exit;
}

// Get the customer ID
$customerId = $_SESSION['customerid'];

// Get tour details from POST request
if (!isset($_POST['destid']) || !isset($_POST['flightid']) || !isset($_POST['hotelid'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required tour information',
        'booking_token' => $_SESSION['booking_token']
    ]);
    exit;
}

$destId = intval($_POST['destid']);
$flightId = intval($_POST['flightid']);
$hotelId = intval($_POST['hotelid']);

// Check if destination, flight, and hotel exist
$checkDestSql = "SELECT destid FROM destination WHERE destid = ?";
$checkFlightSql = "SELECT flightid FROM flights WHERE flightid = ?";
$checkHotelSql = "SELECT hotelid FROM hotels WHERE hotelid = ?";

$destExists = false;
$flightExists = false;
$hotelExists = false;

// Check destination
$stmt = $conn->prepare($checkDestSql);
$stmt->bind_param("i", $destId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $destExists = true;
}
$stmt->close();

// Check flight
$stmt = $conn->prepare($checkFlightSql);
$stmt->bind_param("i", $flightId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $flightExists = true;
}
$stmt->close();

// Check hotel
$stmt = $conn->prepare($checkHotelSql);
$stmt->bind_param("i", $hotelId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $hotelExists = true;
}
$stmt->close();

// Validate required components exist
if (!$destExists || !$flightExists || !$hotelExists) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid destination, flight, or hotel',
        'booking_token' => $_SESSION['booking_token']
    ]);
    exit;
}

// Get the current customer information
$customerSql = "SELECT ssn, username, password, email, gender, bdate, visanum, 
                (CASE WHEN hotelid IS NOT NULL OR flightid IS NOT NULL OR destid IS NOT NULL THEN 1 ELSE 0 END) 
                AS has_existing_tour
                FROM customer 
                WHERE customerid = ?";
$customerStmt = $conn->prepare($customerSql);
$customerStmt->bind_param("i", $customerId);
$customerStmt->execute();
$customerResult = $customerStmt->get_result();

if ($customerResult->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Customer not found',
        'booking_token' => $_SESSION['booking_token']
    ]);
    exit;
}

$customerData = $customerResult->fetch_assoc();
$hasExistingTour = $customerData['has_existing_tour'];
$customerStmt->close();

// Response data array
$responseData = [];

// If the customer already has a tour, add a new customer entry
if ($hasExistingTour) {
    // Insert a new row in the customer table with the same customer info but new tour details
    $insertSql = "INSERT INTO customer (ssn, username, password, email, gender, bdate, hotelid, flightid, destid, visanum) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param(
        "ssssssiiis",
        $customerData['ssn'],
        $customerData['username'],
        $customerData['password'],
        $customerData['email'],
        $customerData['gender'],
        $customerData['bdate'],
        $hotelId,
        $flightId,
        $destId,
        $customerData['visanum']
    );

    if ($insertStmt->execute()) {
        // Get the new booking ID (customer ID)
        $newBookingId = $conn->insert_id;
        $responseData = [
            'booking_id' => $newBookingId,
            'flight_id' => $flightId,
            'hotel_id' => $hotelId,
            'dest_id' => $destId
        ];

        echo json_encode([
            'success' => true,
            'message' => 'New custom tour booked successfully',
            'data' => $responseData,
            'booking_token' => $_SESSION['booking_token']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to book custom tour: ' . $insertStmt->error,
            'booking_token' => $_SESSION['booking_token']
        ]);
    }

    $insertStmt->close();
} else {
    // Update the existing customer record with the new tour details
    $updateSql = "UPDATE customer SET hotelid = ?, flightid = ?, destid = ? WHERE customerid = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("iiii", $hotelId, $flightId, $destId, $customerId);

    if ($updateStmt->execute()) {
        $responseData = [
            'booking_id' => $customerId,
            'flight_id' => $flightId,
            'hotel_id' => $hotelId,
            'dest_id' => $destId
        ];

        echo json_encode([
            'success' => true,
            'message' => 'Custom tour updated successfully',
            'data' => $responseData,
            'booking_token' => $_SESSION['booking_token']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update custom tour: ' . $updateStmt->error,
            'booking_token' => $_SESSION['booking_token']
        ]);
    }

    $updateStmt->close();
}

// Close connection
$conn->close();