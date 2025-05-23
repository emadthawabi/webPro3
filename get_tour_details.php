<?php
// Start the session
session_start();

// Set content type to JSON
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password
$dbname = "pathfinder";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

// Check if tour ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Tour ID is required'
    ]);
    exit;
}

$tourId = intval($_GET['id']);

// Query to get tour details with joined data from all tables INCLUDING locationlink
$sql = "SELECT 
            t.tourid, t.tourname, t.price, t.rating, t.duration, t.image,
            d.destid, d.continent, d.country, d.city, d.description,
            f.flightid, f.airport, f.time AS flight_time, f.begin, f.destid AS flight_destid, f.price AS flight_price, f.type AS flight_type, f.date AS flight_date,
            h.hotelid, h.hotelname, h.price AS hotel_price, h.stars, h.time AS hotel_time, h.numofpeople, h.location, h.locationlink
        FROM tours t
        JOIN destination d ON t.destid = d.destid
        JOIN flights f ON t.flightid = f.flightid
        JOIN hotels h ON t.hotelid = h.hotelid
        WHERE t.tourid = ?";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tourId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch data
    $tourDetails = $result->fetch_assoc();

    // Format data as needed
    $response = [
        'success' => true,
        'data' => [
            // Tour info
            'tour' => [
                'id' => $tourDetails['tourid'],
                'name' => $tourDetails['tourname'],
                'price' => $tourDetails['price'],
                'rating' => $tourDetails['rating'],
                'duration' => $tourDetails['duration'],
                'image' => $tourDetails['image'] ? $tourDetails['image'] : 'placeholder.jpg'
            ],
            // Destination info
            'destination' => [
                'id' => $tourDetails['destid'],
                'continent' => $tourDetails['continent'],
                'country' => $tourDetails['country'],
                'city' => $tourDetails['city'],
                'description' => $tourDetails['description']
            ],
            // Flight info
            'flight' => [
                'id' => $tourDetails['flightid'],
                'airport' => $tourDetails['airport'],
                'time' => $tourDetails['flight_time'],
                'begin' => $tourDetails['begin'],
                'price' => $tourDetails['flight_price'],
                'type' => $tourDetails['flight_type'],
                'date' => $tourDetails['flight_date']
            ],
            // Hotel info
            'hotel' => [
                'id' => $tourDetails['hotelid'],
                'name' => $tourDetails['hotelname'],
                'price' => $tourDetails['hotel_price'],
                'stars' => $tourDetails['stars'],
                'time' => $tourDetails['hotel_time'],
                'numofpeople' => $tourDetails['numofpeople'],
                'location' => $tourDetails['location'],
                'locationlink' => $tourDetails['locationlink']  // Added locationlink
            ]
        ]
    ];

    echo json_encode($response);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Tour not found'
    ]);
}

// Close connection
$stmt->close();
$conn->close();