<?php
// book_custom_tour.php
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
if (!isset($_POST['destid']) || !isset($_POST['flightid']) || !isset($_POST['hotelid'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required tour information'
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

// Validate all components exist
if (!$destExists || !$flightExists || !$hotelExists) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid destination, flight, or hotel'
    ]);
    exit;
}

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
$customerStmt->close();

// Create a tour entry for the custom tour
$createTourSql = "INSERT INTO tours (tourname, destid, flightid, hotelid, price, rating, duration, image) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

// Calculate estimated price and duration (this is simplified)
$price = 0;
$rating = 4.0; // Default rating for custom tours
$duration = 7; // Default duration (1 week)
$tourImage = ''; // No image by default

// Get flight price
$flightSql = "SELECT price FROM flights WHERE flightid = ?";
$stmt = $conn->prepare($flightSql);
$stmt->bind_param("i", $flightId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $flightData = $result->fetch_assoc();
    $flightPrice = floatval(str_replace(['$', ','], '', $flightData['price']));
    $price += $flightPrice;
}
$stmt->close();

// Get hotel price
$hotelSql = "SELECT price FROM hotels WHERE hotelid = ?";
$stmt = $conn->prepare($hotelSql);
$stmt->bind_param("i", $hotelId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $hotelData = $result->fetch_assoc();
    $hotelPrice = floatval(str_replace(['$', ','], '', $hotelData['price']));
    $price += ($hotelPrice * $duration); // Multiply by duration (days)
}
$stmt->close();

// Get destination name for tour name
$destSql = "SELECT city, country FROM destination WHERE destid = ?";
$stmt = $conn->prepare($destSql);
$stmt->bind_param("i", $destId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $destData = $result->fetch_assoc();
    $tourName = "Custom Tour: " . $destData['city'] . ", " . $destData['country'];
} else {
    $tourName = "Custom Tour";
}
$stmt->close();

// Insert the tour
$tourStmt = $conn->prepare($createTourSql);
$tourStmt->bind_param("siiiddis", $tourName, $destId, $flightId, $hotelId, $price, $rating, $duration, $tourImage);
$tourStmt->execute();
$tourId = $tourStmt->insert_id;
$tourStmt->close();

if (!$tourId) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to create custom tour'
    ]);
    exit;
}

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
    $tourId,
    $flightId,
    $hotelId,
    $destId,
    $customerData['visanum']
);

if ($insertStmt->execute()) {
    // Get the new booking ID (customer ID)
    $newBookingId = $conn->insert_id;

    echo json_encode([
        'success' => true,
        'message' => 'Custom tour booked successfully',
        'data' => [
            'booking_id' => $newBookingId,
            'tour_id' => $tourId,
            'flight_id' => $flightId,
            'hotel_id' => $hotelId,
            'dest_id' => $destId
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to book custom tour: ' . $insertStmt->error
    ]);
}

// Close connections
$insertStmt->close();
$conn->close();
