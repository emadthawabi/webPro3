<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password
$dbname = "pathfinder";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get all destinations
function getDestinations($conn) {
    $sql = "SELECT * FROM destination ORDER BY continent, country, city";
    $result = $conn->query($sql);
    $destinations = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $destinations[] = $row;
        }
    }

    return $destinations;
}

// Function to get all flights
function getFlights($conn) {
    $sql = "SELECT f.*, d.continent, d.country, d.city 
            FROM flights f
            JOIN destination d ON f.destid = d.destid";
    $result = $conn->query($sql);
    $flights = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $flights[] = $row;
        }
    }

    return $flights;
}

// Function to get all hotels
function getHotels($conn) {
    $sql = "SELECT h.*, d.continent, d.country, d.city 
            FROM hotels h
            JOIN destination d ON h.destid = d.destid";
    $result = $conn->query($sql);
    $hotels = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $hotels[] = $row;
        }
    }

    return $hotels;
}

// Get data from database
$destinations = getDestinations($conn);
$flights = getFlights($conn);
$hotels = getHotels($conn);

// Set page title
$pageTitle = "CustomTour";
?>
<!DOCTYPE html>

<html lang="en">
<head>
    <?php include 'head.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>

<link rel="stylesheet" type="text/css" href="css/CustomTour.css">
<!-- Hero Section -->
<section class="tours-hero custom-tour-hero">
    <div class="container">
        <h1 data-aos="fade-down">Build Your Dream Tour</h1>
        <p data-aos="fade-up">Create a personalized travel experience by selecting your destination, flights, and accommodations</p>
    </div>
</section>

<!-- Main Build Tour Section -->
<section class="build-tour-section">
    <div class="container">
        <!-- Progress Tracker -->
        <div class="progress-tracker" data-aos="fade-up">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <div class="progress-steps">
                <div class="step active" data-step="destination">
                    <div class="step-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="step-text">Destination</div>
                </div>
                <div class="step" data-step="flights">
                    <div class="step-icon"><i class="fas fa-plane"></i></div>
                    <div class="step-text">Flights</div>
                </div>
                <div class="step" data-step="hotels">
                    <div class="step-icon"><i class="fas fa-hotel"></i></div>
                    <div class="step-text">Hotels</div>
                </div>
                <div class="step" data-step="review">
                    <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="step-text">Review</div>
                </div>
            </div>
        </div>

        <!-- Tour Builder Container -->
        <div class="tour-builder" data-aos="fade-up">
            <!-- Step 1: Destination -->
            <div class="build-step active" id="destinationStep">
                <div class="step-header">
                    <h2>Choose Your Destination</h2>
                    <p>Select where you want to go for your dream vacation</p>
                </div>

                <div class="filter-container">
                    <div class="filter-options">
                        <div class="filter-group">
                            <label>Continent</label>
                            <select id="continentSelect">
                                <option value="">All Continents</option>
                                <?php
                                // Get unique continents
                                $continents = [];
                                foreach ($destinations as $destination) {
                                    $continent = strtolower($destination['continent']);
                                    if (!in_array($continent, $continents)) {
                                        $continents[] = $continent;
                                        echo "<option value=\"$continent\">" . ucfirst($continent) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label>Country</label>
                            <select id="countrySelect" disabled>
                                <option value="">Select Continent First</option>
                            </select>
                        </div>

                        <div class="filter-group search-group">
                            <label>Search city</label>
                            <input type="text" id="destinationSearch" placeholder="Search cities...">
                        </div>
                    </div>
                </div>

                <div class="destination-grid">
                    <?php foreach ($destinations as $destination): ?>
                        <div class="destination-card"
                             data-destination="<?php echo strtolower($destination['city']); ?>"
                             data-country="<?php echo strtolower($destination['country']); ?>"
                             data-continent="<?php echo strtolower($destination['continent']); ?>"
                             data-destid="<?php echo $destination['destid']; ?>">
                            <div class="destination-image">
                                <?php if (!empty($destination['destimage'])): ?>
                                    <img src="uploadImages/<?php echo htmlspecialchars($destination['destimage']); ?>" alt="<?php echo htmlspecialchars($destination['city']); ?>">
                                <?php else: ?>
                                    <img src="images/destination-placeholder.jpg" alt="<?php echo htmlspecialchars($destination['city']); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="destination-info">
                                <h3><?php echo htmlspecialchars($destination['city']); ?></h3>
                                <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($destination['country']); ?>, <?php echo htmlspecialchars($destination['continent']); ?></p>
                                <div class="destination-highlights">
                                    <span><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($destination['description']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="step-navigation">
                    <button class="next-btn" id="destinationNextBtn" disabled>Continue to Flights <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- Step 3: Flights -->
            <div class="build-step" id="flightsStep">
                <div class="step-header">
                    <h2>Choose Your Flight</h2>
                    <p>Select the flight that best fits your schedule</p>
                </div>

                <div class="flights-container">
                    <!-- Flights -->
                    <div class="flight-section">
                        <h3>Available Flights</h3>
<!--                        <div class="flight-filters">-->
<!--                            <div class="filter-toggle">-->
<!--                                <label class="switch">-->
<!--                                    <input type="checkbox" id="directFlightsOnly">-->
<!--                                    <span class="slider round"></span>-->
<!--                                </label>-->
<!--                                <span>Direct flights only</span>-->
<!--                            </div>-->
<!--                            <div class="sort-by">-->
<!--                                <label>Sort by:</label>-->
<!--                                <select id="flightSort">-->
<!--                                    <option value="price">Price: Low to High</option>-->
<!--                                    <option value="time">Departure: Earliest</option>-->
<!--                                </select>-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="flight-cards" id="flights">
                            <?php foreach ($flights as $flight): ?>
                                <div class="flight-card" data-flightid="<?php echo $flight['flightid']; ?>" data-destid="<?php echo $flight['destid']; ?>">
                                    <div class="flight-airline">
<!--                                        <img src="images/airline-placeholder.png" alt="Airline Logo">-->
                                        <span><?php echo htmlspecialchars($flight['airport']); ?></span>
                                    </div>
                                    <div class="flight-details">
                                        <div class="flight-time">
                                            <div class="departure">
                                                <span class="time"><?php echo htmlspecialchars($flight['time']); ?></span>
                                                <span class="airport"><?php echo htmlspecialchars($flight['begin']); ?></span>
                                            </div>
                                            <div class="flight-duration">
                                                <span class="duration">Flight to <?php echo htmlspecialchars($flight['city']); ?></span>
                                                <div class="duration-line">
                                                    <div class="plane-icon"><i class="fas fa-plane"></i></div>
                                                </div>
                                                <span class="stops">Direct</span>
                                            </div>
                                            <div class="arrival">
                                                <span class="time"><?php echo htmlspecialchars($flight['date']); ?></span>
                                                <span class="airport"><?php echo htmlspecialchars($flight['city']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flight-price">
                                        <span class="price"><?php echo htmlspecialchars($flight['price']); ?></span>
                                        <div class="select-flight">
                                            <button class="select-btn">Select</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php if (count($flights) === 0): ?>
                                <div class="no-flights-message">
                                    <p>No flights are currently available for this destination. Please try a different destination.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="step-navigation">
                    <button class="back-btn" id="flightsBackBtn"><i class="fas fa-arrow-left"></i> Back to Destination</button>
                    <button class="next-btn" id="flightsNextBtn" disabled>Continue to Hotels <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- Step 4: Hotels -->
            <div class="build-step" id="hotelsStep">
                <div class="step-header">
                    <h2>Select Your Accommodation</h2>
                    <p>Choose where you want to stay during your trip</p>
                </div>

                <div class="hotels-container">
                    <div class="hotel-filters">
                        <div class="filter-group">
                            <label>Price Range</label>
                            <select id="hotelPriceFilter">
                                <option value="">Any Price</option>
                                <option value="budget">Budget (Under $100/night)</option>
                                <option value="moderate">Moderate ($100-$200/night)</option>
                                <option value="luxury">Luxury ($200+/night)</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Star Rating</label>
                            <select id="hotelStarFilter">
                                <option value="">Any Rating</option>
                                <option value="3">3+ Stars</option>
                                <option value="4">4+ Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                    </div>

                    <div class="hotel-cards" id="hotelList">
                        <?php foreach ($hotels as $hotel): ?>
                            <div class="hotel-card" data-hotelid="<?php echo $hotel['hotelid']; ?>" data-destid="<?php echo $hotel['destid']; ?>">
                                <div class="hotel-image">
                                    <?php if (!empty($hotel['hotelimage'])): ?>
                                        <img src="uploadImages/<?php echo htmlspecialchars($hotel['hotelimage']); ?>" alt="<?php echo htmlspecialchars($hotel['hotelname']); ?>">
                                    <?php else: ?>
                                        <img src="images/hotel-placeholder.jpg" alt="<?php echo htmlspecialchars($hotel['hotelname']); ?>">
                                    <?php endif; ?>
                                    <div class="hotel-rating">
                                        <?php
                                        $stars = intval($hotel['stars']);
                                        for ($i = 0; $i < $stars; $i++) {
                                            echo '<i class="fas fa-star"></i>';
                                        }
                                        for ($i = $stars; $i < 5; $i++) {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="hotel-info">
                                    <h3><?php echo htmlspecialchars($hotel['hotelname']); ?></h3>
                                    <p class="hotel-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($hotel['location']); ?>, <?php echo htmlspecialchars($hotel['city']); ?></p>
                                    <div class="hotel-amenities">
                                        <span><i class="fas fa-user-friends"></i> <?php echo htmlspecialchars($hotel['numofpeople']); ?> people</span>
                                        <span><i class="fas fa-clock"></i> Check-in: <?php echo htmlspecialchars($hotel['time']); ?></span>
                                    </div>
                                    <div class="hotel-price">
                                        <span class="price"><?php echo htmlspecialchars($hotel['price']); ?>$</span>
                                        <span class="per-night">per night</span>
                                    </div>
                                    <button class="select-btn">Select Hotel</button>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (count($hotels) === 0): ?>
                            <div class="no-hotels-message">
                                <p>No hotels are currently available for this destination. Please try a different destination.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="step-navigation">
                    <button class="back-btn" id="hotelsBackBtn"><i class="fas fa-arrow-left"></i> Back to Flights</button>
                    <button class="next-btn" id="hotelsNextBtn" disabled>Continue to Review <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- Step 6: Review -->
            <div class="build-step" id="reviewStep">
                <div class="step-header">
                    <h2>Review Your Custom Tour</h2>
                    <p>Review and finalize your personalized travel itinerary</p>
                </div>

                <div class="review-container">
                    <div class="review-summary">
                        <div class="summary-header">
                            <h3>Your Customized Tour</h3>
                            <div class="total-price">
                                <span>Total:</span>
                                <span class="price" id="totalPrice">$0.00</span>
                            </div>
                        </div>

                        <div class="summary-card destination-summary">
                            <div class="summary-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="summary-details">
                                <h4>Destination</h4>
                                <p id="reviewDestination">Select a destination</p>
                            </div>
                            <button class="edit-btn" data-step="destination"><i class="fas fa-edit"></i></button>
                        </div>

                        <div class="summary-card flights-summary">
                            <div class="summary-icon">
                                <i class="fas fa-plane"></i>
                            </div>
                            <div class="summary-details">
                                <h4>Flight</h4>
                                <div id="reviewFlights">
                                    <p>Select a flight</p>
                                </div>
                            </div>
                            <button class="edit-btn" data-step="flights"><i class="fas fa-edit"></i></button>
                        </div>

                        <div class="summary-card hotel-summary">
                            <div class="summary-icon">
                                <i class="fas fa-hotel"></i>
                            </div>
                            <div class="summary-details">
                                <h4>Accommodation</h4>
                                <div id="reviewHotel">
                                    <p>Select a hotel</p>
                                </div>
                            </div>
                            <button class="edit-btn" data-step="hotels"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>

                    <div class="booking-sidebar">
                        <div class="price-breakdown">
                            <h4>Price Breakdown</h4>
                            <div class="price-item">
                                <span>Flight</span>
                                <span id="flightsCost">$0.00</span>
                            </div>
                            <div class="price-item">
                                <span>Accommodation</span>
                                <span id="hotelCost">$0.00</span>
                            </div>
                            <div class="price-item taxes">
                                <span>Taxes & Fees</span>
                                <span id="taxesCost">$0.00</span>
                            </div>
                            <div class="price-item total">
                                <span>Total</span>
                                <span id="sidebarTotalPrice">$0.00</span>
                            </div>
                        </div>
                        <div class="booking-actions">
                            <button class="book-btn" id="bookTourBtn">Book Your Tour</button>
                            <p class="booking-note">No payment required until final confirmation</p>
                        </div>
                    </div>
                </div>

                <div class="step-navigation">
                    <button class="back-btn" id="reviewBackBtn"><i class="fas fa-arrow-left"></i> Back to Hotels</button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<!-- Script for booking the tour -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Booking button click handler
        const bookTourBtn = document.getElementById('bookTourBtn');

        if (bookTourBtn) {
            bookTourBtn.addEventListener('click', function() {
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                // User is logged in, proceed with booking
                const selectedDestId = tourSelections.destination.id;
                const selectedFlightId = tourSelections.flights.id;
                const selectedHotelId = tourSelections.hotel.id;

                // Create form data
                const formData = new FormData();
                formData.append('destid', selectedDestId);
                formData.append('flightid', selectedFlightId);
                formData.append('hotelid', selectedHotelId);

                // Show loading state
                this.textContent = 'Booking...';
                this.disabled = true;

                // Send booking request
                fetch('book_custom_tour.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Your tour has been booked successfully!');
                            // Redirect to my bookings page
                            window.location.href = 'my_bookings.php';
                        } else {
                            alert('There was a problem booking your tour: ' + data.message);
                            // Reset button
                            this.textContent = 'Book Your Tour';
                            this.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while booking your tour. Please try again.');
                        // Reset button
                        this.textContent = 'Book Your Tour';
                        this.disabled = false;
                    });
                <?php else: ?>
                // User is not logged in, show login modal
                alert('Please log in to book a tour');
                document.getElementById('authModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
                <?php endif; ?>
            });
        }
    });
</script>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="js/custom.js"></script>
<script src="js/custom-tour.js"></script>
</body>
</html>