<?php
// cancel_booking.php
session_start();

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode([
        'success' => false,
        'message' => 'Not logged in'
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

// Get customer ID
$customerId = $_SESSION['customerid'];

// Check if booking ID was provided
if (!isset($_POST['booking_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Booking ID is required'
    ]);
    exit;
}

$bookingId = intval($_POST['booking_id']);

// Verify this booking belongs to the customer
$checkSql = "SELECT customerid, email FROM customer WHERE customerid = ? AND email = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("is", $bookingId, $_SESSION['email']);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid booking or not authorized'
    ]);
    $checkStmt->close();
    $conn->close();
    exit;
}

// Delete the booking entry
$deleteSql = "DELETE FROM customer WHERE customerid = ?";
$deleteStmt = $conn->prepare($deleteSql);
$deleteStmt->bind_param("i", $bookingId);

if ($deleteStmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Booking cancelled successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to cancel booking: ' . $deleteStmt->error
    ]);
}

// Close connections
$checkStmt->close();
$deleteStmt->close();
$conn->close();