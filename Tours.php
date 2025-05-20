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

// Function to get all tours with details
function getTours($conn) {
    $sql = "SELECT t.tourid, t.tourname, t.price, t.rating, t.duration, t.image, 
                  d.continent, d.country, d.city, d.description
           FROM tours t
           JOIN destination d ON t.destid = d.destid";

    $result = $conn->query($sql);
    $tours = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Define the budget category based on price
            if ($row['price'] < 1000) {
                $budget = 'budget';
            } elseif ($row['price'] >= 1000 && $row['price'] <= 3000) {
                $budget = 'standard';
            } else {
                $budget = 'luxury';
            }

            // Define the duration category based on days
            if ($row['duration'] <= 3) {
                $duration = 'short';
            } elseif ($row['duration'] > 3 && $row['duration'] <= 7) {
                $duration = 'medium';
            } else {
                $duration = 'long';
            }

            // Add processed data to the tours array
            $row['budget_category'] = $budget;
            $row['duration_category'] = $duration;
            $tours[] = $row;
        }
    }

    return $tours;
}

// Get all tours
$tours = getTours($conn);

// Set page title
$pageTitle = "Tours";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <!-- Add custom CSS for tour details modal -->
    <link rel="stylesheet" href="css/tour_details_modal.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="tours-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="title" data-aos="fade-down">Explore Our Tours</h1>
            <div class="subtitle" data-aos="fade-up">Unforgettable Journeys</div>
            <p data-aos="fade-up">Discover amazing adventures around the world with our expertly crafted tours. From exotic beaches to historic landmarks, we have the perfect experience waiting for you.</p>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="filter-section">
    <div class="container">
        <div class="filter-container" data-aos="fade-up">
            <div class="filter-header">
                <h3>Find Your Perfect Tour</h3>
                <button id="clearFilters" class="clear-btn">Clear Filters</button>
            </div>

            <div class="filter-options">
                <div class="filter-group">
                    <label>Continent</label>
                    <select id="continentFilter">
                        <option value="">All Continents</option>
                        <option value="europe">Europe</option>
                        <option value="asia">Asia</option>
                        <option value="africa">Africa</option>
                        <option value="north-america">North America</option>
                        <option value="south-america">South America</option>
                        <!--                        <option value="australia">Australia & Oceania</option>-->
                    </select>
                </div>

                <div class="filter-group">
                    <label>Country</label>
                    <select id="countryFilter" disabled>
                        <option value="">Select Continent First</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Duration</label>
                    <select id="durationFilter">
                        <option value="">Any Duration</option>
                        <option value="short">Short (1-3 days)</option>
                        <option value="medium">Medium (4-7 days)</option>
                        <option value="long">Long (8+ days)</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Budget</label>
                    <select id="budgetFilter">
                        <option value="">Any Budget</option>
                        <option value="budget">Budget (Under $1000)</option>
                        <option value="standard">Standard ($1000-$3000)</option>
                        <option value="luxury">Luxury ($3000+)</option>
                    </select>
                </div>

                <div class="filter-group search-group">
                    <label>Search</label>
                    <input type="text" id="searchInput" placeholder="Search tours...">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tours Display Section -->
<section class="tours-display">
    <div class="container">
        <div class="tour-results-header">
            <h2>Available Tours <span id="tourCount" class="tour-count">(<?php echo count($tours); ?>)</span></h2>
            <!--            <div class="sort-options">-->
            <!--                <label>Sort by:</label>-->
            <!--                <select id="sortOptions">-->
            <!--                    <option value="popular">Most Popular</option>-->
            <!--                    <option value="price-asc">Price: Low to High</option>-->
            <!--                    <option value="price-desc">Price: High to Low</option>-->
            <!--                    <option value="duration">Duration</option>-->
            <!--                    <option value="rating">Rating</option>-->
            <!--                </select>-->
            <!--            </div>-->
        </div>

        <div class="tour-grid" id="tourGrid">
            <?php if (count($tours) > 0): ?>
                <?php foreach ($tours as $tour): ?>
                    <div class="tour-card"
                         data-continent="<?php echo strtolower($tour['continent']); ?>"
                         data-country="<?php echo strtolower($tour['country']); ?>"
                         data-duration="<?php echo $tour['duration_category']; ?>"
                         data-budget="<?php echo $tour['budget_category']; ?>"
                         data-aos="fade-up">
                        <div class="tour-image">
                            <?php if (!empty($tour['image'])): ?>
                                <img src="uploadImages/<?php echo htmlspecialchars($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['tourname']); ?>">
                            <?php else: ?>
                                <img src="uploadImages/placeholder.jpg" alt="Tour Image Placeholder">
                            <?php endif; ?>

                            <?php if ($tour['rating'] >= 4.5): ?>
                                <div class="tour-badge">POPULAR</div>
                            <?php endif; ?>
                        </div>
                        <div class="tour-info">
                            <div class="tour-rating">
                                <span class="stars">
                                    <?php
                                    $full_stars = floor($tour['rating']);
                                    $half_star = ($tour['rating'] - $full_stars) >= 0.5;

                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $full_stars) {
                                            echo '<i class="fas fa-star"></i>';
                                        } elseif ($i == $full_stars + 1 && $half_star) {
                                            echo '<i class="fas fa-star-half-alt"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </span>
                                <span class="rating-count"><?php echo number_format($tour['rating'], 1); ?> </span>
                            </div>
                            <h3><?php echo htmlspecialchars($tour['tourname']); ?></h3>
                            <p class="tour-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($tour['city']); ?>, <?php echo htmlspecialchars($tour['country']); ?></p>
                            <ul class="tour-features">
<!--                                <li><i class="fas fa-calendar-alt"></i> --><?php //echo $tour['duration']; ?><!-- days, --><?php //echo $tour['duration'] - 1; ?><!-- nights</li>-->
<!--                                <li><i class="fas fa-plane"></i> Round-trip flight included</li>-->
<!--                                <li><i class="fas fa-hiking"></i> --><?php //echo htmlspecialchars($tour['description']); ?><!--</li>-->
                            </ul>
                            <div class="tour-footer">
                                <div class="tour-price">
                                    <span class="price">$<?php echo number_format($tour['price'], 2); ?></span>
                                    <span class="per-person">per person</span>
                                </div>
                                <!-- Modified to use data attribute instead of href for tour ID -->
                                <a href="#" class="view-details" data-tour-id="<?php echo $tour['tourid']; ?>">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-tours-message">
                    <p>No tours are currently available. Please check back later.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="no-results" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>No tours match your search criteria</h3>
            <p>Try adjusting your filters or search terms</p>
            <button id="resetFilters" class="reset-btn">Reset Filters</button>
        </div>

        <!-- Pagination - Only show if there are enough tours -->
        <?php if (count($tours) > 10): ?>
            <div class="pagination">
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <span class="page-dots">...</span>
                <button class="page-btn"><?php echo ceil(count($tours) / 10); ?></button>
                <button class="page-next">Next <i class="fas fa-chevron-right"></i></button>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Tour Details Modal -->
<div id="tourDetailsModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-tour-name">Tour Details</h2>
            <button class="modal-close" id="closeModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="details-loading">
                <div class="spinner"></div>
                <p>Loading tour details...</p>
            </div>

            <div class="details-content" style="display: none;">
                <div class="details-overview">
                    <div class="details-image">
                        <img id="modal-tour-image" src="uploadImages/placeholder.jpg" alt="Tour image">
                    </div>
                    <div class="details-summary">
                        <div class="details-rating">
                            <span class="stars" id="modal-tour-stars"></span>
                            <span class="rating-count" id="modal-tour-rating">4.5</span>
                        </div>
                        <div class="details-price">
                            <span class="price" id="modal-tour-price">$0.00</span>
                            <span class="per-person">per person</span>
                        </div>
                        <div class="details-duration">
                            <i class="fas fa-clock"></i>
                            <span id="modal-tour-duration">0 days, 0 nights</span>
                        </div>
                        <a href="#" class="btn btn-primary book-tour-btn" id="bookTourBtn">Book This Tour</a>
                    </div>
                </div>

                <div class="details-tabs">
                    <button class="tab-btn active" data-tab="destination">Destination</button>
                    <button class="tab-btn" data-tab="flights">Flight Details</button>
                    <button class="tab-btn" data-tab="hotels">Hotel Details</button>
                </div>

                <div class="tab-content">
                    <!-- Destination Tab -->
                    <div class="tab-pane active" id="destination-tab">
                        <div class="destination-details">
                            <h3>Destination Information</h3>
                            <div class="detail-item">
                                <span class="detail-label">Continent:</span>
                                <span class="detail-value" id="modal-continent"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Country:</span>
                                <span class="detail-value" id="modal-country"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">City:</span>
                                <span class="detail-value" id="modal-city"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Description:</span>
                                <span class="detail-value" id="modal-description"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Flights Tab -->
                    <div class="tab-pane" id="flights-tab">
                        <div class="flight-details">
                            <h3>Flight Information</h3>
                            <div class="detail-item">
                                <span class="detail-label">Airline/Airport:</span>
                                <span class="detail-value" id="modal-airport"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Departure:</span>
                                <span class="detail-value" id="modal-flight-time"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Origin:</span>
                                <span class="detail-value" id="modal-flight-begin"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Flight Type:</span>
                                <span class="detail-value" id="modal-flight-type"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Flight Date:</span>
                                <span class="detail-value" id="modal-flight-date"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Flight Price:</span>
                                <span class="detail-value price-highlight" id="modal-flight-price"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Hotels Tab -->
                    <div class="tab-pane" id="hotels-tab">
                        <div class="hotel-details">
                            <h3>Hotel Information</h3>
                            <div class="detail-item">
                                <span class="detail-label">Hotel Name:</span>
                                <span class="detail-value" id="modal-hotel-name"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Rating:</span>
                                <span class="detail-value" id="modal-hotel-stars"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Check-in Time:</span>
                                <span class="detail-value" id="modal-hotel-time"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Accommodates:</span>
                                <span class="detail-value" id="modal-hotel-people"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Location:</span>
                                <span class="detail-value" id="modal-hotel-location"></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Price per night:</span>
                                <span class="detail-value price-highlight" id="modal-hotel-price"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
<script src="js/custom.js"></script>
<script src="js/tours.js"></script>
<!-- Add custom JS for tour details modal -->
<script src="js/tour_details_modal.js"></script>

<?php
// Close the database connection
$conn->close();
?>


// Add this script to your tours.php page, right before the closing </body> tag
<script>
    // Check if user just logged in and there's a pending tour booking
    document.addEventListener('DOMContentLoaded', function() {
        // Check if user is logged in and there's a pending booking
        <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        const pendingTourId = sessionStorage.getItem('pendingBookTourId');
        if (pendingTourId) {
            // Clear the pending tour ID
            sessionStorage.removeItem('pendingBookTourId');

            // Show confirmation to user
            if (confirm('Would you like to book the tour you were viewing?')) {
                // Create form data with tour ID
                const formData = new FormData();
                formData.append('tour_id', pendingTourId);

                // Send AJAX request to book tour
                fetch('book_tour.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Your tour has been booked successfully!');
                            // Redirect to bookings page
                            window.location.href = 'my_bookings.php';
                        } else {
                            alert('There was a problem booking your tour: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('There was a server error. Please try again later.');
                    });
            }
        }
        <?php endif; ?>
    });
</script>
</body>
</html>