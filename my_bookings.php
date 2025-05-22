<?php
// my_bookings.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to login page
    header("Location: login.php?redirect=my_bookings.php");
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
    die("Connection failed: " . $conn->connect_error);
}

// Get customer ID
$customerId = $_SESSION['customerid'];

// Get customer bookings with all details including tour information and destination image
$sql = "SELECT c.customerid, c.username, c.email, 
               t.tourid, t.tourname, t.price as tour_price, t.rating, t.duration, t.image,
               d.continent, d.country, d.city, d.description, d.destimage,
               f.airport, f.time AS flight_time, f.begin, f.price AS flight_price, f.type AS flight_type, f.date,
               h.hotelname, h.price AS hotel_price, h.stars, h.numofpeople, h.location
        FROM customer c
        JOIN destination d ON c.destid = d.destid
        JOIN flights f ON c.flightid = f.flightid
        LEFT JOIN hotels h ON c.hotelid = h.hotelid
        LEFT JOIN tours t ON c.tourid = t.tourid
        WHERE c.email = ? AND (c.hotelid IS NOT NULL OR c.flightid IS NOT NULL OR c.destid IS NOT NULL)
        ORDER BY c.customerid DESC"; // Most recent bookings first

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']); // Use email to find all bookings for this user
$stmt->execute();
$result = $stmt->get_result();

$customerBookings = $result->fetch_all(MYSQLI_ASSOC);

foreach ($customerBookings as &$booking) {
    if (empty($booking['tourid'])) {
        // Calculate total price for non-tour bookings
        $booking['tour_price'] = (floatval($booking['flight_price']) + (floatval($booking['hotel_price']) * 7));
        $booking['tourname'] = 'My '.$booking['city'] . ' Adventure';
        $booking['rating'] = 4.0;
        $booking['duration'] = 7;
        $booking['image'] = null; // Will use destination image as fallback
    }
}

$pageTitle = "My Bookings";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="css/my_bookings.css">
</head>
<body>
<?php include 'header.php'; ?>

<!-- My Bookings Hero Section -->
<section class="bookings-hero">
    <div class="container">
        <h1 data-aos="fade-down">My Bookings</h1>
        <p data-aos="fade-up">View and manage your travel adventures</p>
    </div>
</section>

<!-- My Bookings Content Section -->
<section class="bookings-content">
    <div class="container">
        <?php if (count($customerBookings) > 0): ?>
            <h2 class="bookings-title">Your Booked Tours (<?php echo count($customerBookings); ?>)</h2>

            <?php foreach($customerBookings as $index => $booking): ?>
                <div class="booking-details" data-aos="fade-up" data-booking-id="<?php echo $booking['customerid']; ?>">
                    <div class="booking-header">
                        <h3>Booking #<?php echo $booking['customerid']; ?></h3>
                        <div class="booking-actions">
                            <button class="btn btn-secondary print-booking">
                                <i class="fas fa-print"></i> Print
                            </button>

                        </div>
                    </div>

                    <div class="booking-card">
                        <div class="booking-image">
                            <?php
                            $imagePath = '';
                            $imageFound = false;

                            // First, try to find the tour image if it exists
                            if (!empty($booking['image'])) {
                                // Check multiple possible image directories for tour images
                                $possiblePaths = [
                                    'uploadImages/' . $booking['image'],
                                    'images/' . $booking['image'],
                                    'assets/images/' . $booking['image'],
                                    $booking['image']
                                ];

                                foreach ($possiblePaths as $path) {
                                    if (file_exists($path)) {
                                        $imagePath = $path;
                                        $imageFound = true;
                                        break;
                                    }
                                }
                            }

                            // If no tour image found, try to use destination image
                            if (!$imageFound && !empty($booking['destimage'])) {
                                $destImagePaths = [
                                    'uploadImages/' . $booking['destimage'],
                                    'images/' . $booking['destimage'],
                                    'assets/images/' . $booking['destimage'],
                                    'images/destinations/' . $booking['destimage'],
                                    'assets/images/destinations/' . $booking['destimage'],
                                    $booking['destimage']
                                ];

                                foreach ($destImagePaths as $path) {
                                    if (file_exists($path)) {
                                        $imagePath = $path;
                                        $imageFound = true;
                                        break;
                                    }
                                }
                            }

                            if ($imageFound && !empty($imagePath)): ?>
                                <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($booking['tourname']); ?>">
                            <?php else: ?>
                                <!-- Fallback to a placeholder when no image is found -->
                                <div class="image-placeholder">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <p><?php echo htmlspecialchars($booking['city']); ?></p>
                                    <small><?php echo htmlspecialchars($booking['country']); ?></small>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="booking-info">
                            <h3><?php echo htmlspecialchars($booking['tourname']); ?></h3>
                            <div class="booking-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo htmlspecialchars($booking['city']); ?>,
                                <?php echo htmlspecialchars($booking['country']); ?>
                                (<?php echo htmlspecialchars($booking['continent']); ?>)
                            </div>

                            <div class="booking-highlights">
                                <div class="highlight-item">
                                    <span class="highlight-label">Duration:</span>
                                    <span class="highlight-value"><?php echo $booking['duration']; ?> days</span>
                                </div>
                                <div class="highlight-item">
                                    <span class="highlight-label">Total Price:</span>
                                    <span class="highlight-value">$<?php echo number_format($booking['tour_price'], 2); ?></span>
                                </div>
                                <div class="highlight-item">
                                    <span class="highlight-label">Rating:</span>
                                    <span class="highlight-value">
                                        <?php
                                        $rating = floatval($booking['rating']);
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<i class="fas fa-star"></i>';
                                            } elseif ($i - 0.5 <= $rating) {
                                                echo '<i class="fas fa-star-half-alt"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        echo ' (' . $rating . '/5)';
                                        ?>
                                    </span>
                                </div>
                                <?php if (!empty($booking['tourid'])): ?>
                                    <div class="highlight-item">
                                        <span class="highlight-label">Tour ID:</span>
                                        <span class="highlight-value">#<?php echo htmlspecialchars($booking['tourid']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="booking-tabs">
                                <button class="tab-btn active" data-tab="flight-<?php echo $index; ?>">Flight Details</button>
                                <button class="tab-btn" data-tab="hotel-<?php echo $index; ?>">Hotel Details</button>
                                <button class="tab-btn" data-tab="destination-<?php echo $index; ?>">Destination</button>
                            </div>

                            <div class="booking-tab-content">
                                <!-- Flight Tab -->
                                <div class="tab-pane active" id="flight-<?php echo $index; ?>-tab">
                                    <div class="detail-item">
                                        <span class="detail-label">Airline/Airport:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($booking['airport']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Departure Time:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($booking['flight_time']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Origin:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($booking['begin']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Flight Type:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($booking['flight_type']); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Flight Date:</span>
                                        <span class="detail-value"><?php echo date('F j, Y', strtotime($booking['date'])); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Flight Price:</span>
                                        <span class="detail-value">$<?php echo number_format($booking['flight_price'], 2); ?></span>
                                    </div>
                                </div>

                                <!-- Hotel Tab -->
                                <div class="tab-pane" id="hotel-<?php echo $index; ?>-tab">
                                    <?php if (!empty($booking['hotelname'])): ?>
                                        <div class="detail-item">
                                            <span class="detail-label">Hotel Name:</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($booking['hotelname']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Rating:</span>
                                            <span class="detail-value"><?php echo $booking['stars']; ?> stars</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Accommodates:</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($booking['numofpeople']); ?> people</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Location:</span>
                                            <span class="detail-value"><?php echo htmlspecialchars($booking['location']); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Price per night:</span>
                                            <span class="detail-value">$<?php echo number_format($booking['hotel_price'], 2); ?></span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Total hotel cost (7 nights):</span>
                                            <span class="detail-value">$<?php echo number_format($booking['hotel_price'] * 7, 2); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="detail-item">
                                            <span class="detail-value">No hotel booked for this trip.</span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Destination Tab -->
                                <div class="tab-pane" id="destination-<?php echo $index; ?>-tab">
                                    <div class="detail-item">
                                        <span class="detail-label">Continent:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars(ucfirst($booking['continent'])); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Country:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars(ucfirst($booking['country'])); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">City:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars(ucfirst($booking['city'])); ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Description:</span>
                                        <span class="detail-value"><?php echo htmlspecialchars($booking['description']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-bookings" data-aos="fade-up">
                <div class="no-bookings-icon">
                    <i class="fas fa-suitcase"></i>
                </div>
                <h2>No Bookings Found</h2>
                <p>You haven't booked any tours yet. Start planning your next adventure today!</p>
                <a href="Tours.php" class="btn btn-primary">Explore Tours</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
<script src="js/custom.js"></script>
<script src="js/my_bookings.js"></script>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>
</body>
</html>