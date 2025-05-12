<?php
// Start the session
session_start();

// Set page title
$pageTitle = "Home";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>

<!-- Tours Hero Section -->
<section class="tours-hero">
    <div class="container">
        <h1 data-aos="fade-down">Explore Our Tours</h1>
        <p data-aos="fade-up">Discover amazing adventures around the world with our expertly crafted tours</p>
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
                        <option value="australia">Australia & Oceania</option>
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
            <h2>Available Tours <span id="tourCount" class="tour-count">(12)</span></h2>
            <div class="sort-options">
                <label>Sort by:</label>
                <select id="sortOptions">
                    <option value="popular">Most Popular</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="duration">Duration</option>
                    <option value="rating">Rating</option>
                </select>
            </div>
        </div>

        <div class="tour-grid" id="tourGrid">
            <!-- Europe Tours -->
            <div class="tour-card" data-continent="europe" data-country="france" data-duration="medium" data-budget="standard" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/paris.webp" alt="Paris City Tour">
                    <div class="tour-badge">POPULAR</div>
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </span>
                        <span class="rating-count">4.5 (128 reviews)</span>
                    </div>
                    <h3>Paris Explorer</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> Paris, France</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 5 days, 4 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Eiffel Tower, Louvre Museum, Seine River Cruise</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$1,899</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>

            <div class="tour-card" data-continent="europe" data-country="italy" data-duration="medium" data-budget="standard" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/rome.jpg" alt="Rome Cultural Tour">
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </span>
                        <span class="rating-count">4.0 (95 reviews)</span>
                    </div>
                    <h3>Roman Holiday</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> Rome, Italy</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 6 days, 5 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Colosseum, Vatican, Roman Forum</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$2,199</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>

            <div class="tour-card" data-continent="europe" data-country="greece" data-duration="long" data-budget="standard" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/Greek.jpg" alt="Greek Island Hopping">
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </span>
                        <span class="rating-count">4.9 (210 reviews)</span>
                    </div>
                    <h3>Greek Island Hopping</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> Athens, Mykonos, Santorini</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 10 days, 9 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Acropolis, Blue Domes, Volcanic Beaches</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$2,899</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>

            <!-- Asia Tours -->
            <div class="tour-card" data-continent="asia" data-country="japan" data-duration="long" data-budget="luxury" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/japan.webp" alt="Japan Explorer">
                    <div class="tour-badge">TRENDING</div>
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </span>
                        <span class="rating-count">4.7 (156 reviews)</span>
                    </div>
                    <h3>Japan Heritage Tour</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> Tokyo, Kyoto, Osaka</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 12 days, 11 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Mt. Fuji, Cherry Blossoms, Temple Tours</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$3,599</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>

            <div class="tour-card" data-continent="asia" data-country="thailand" data-duration="medium" data-budget="budget" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/Thailand.webp" alt="Thailand Adventure">
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </span>
                        <span class="rating-count">4.2 (118 reviews)</span>
                    </div>
                    <h3>Thailand Beach & Culture</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> Bangkok, Phuket, Krabi</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 7 days, 6 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Temple Tours, Island Hopping, Cooking Class</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$999</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>

            <!-- Africa Tours -->
            <div class="tour-card" data-continent="africa" data-country="south-africa" data-duration="long" data-budget="luxury" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/cape.jpg" alt="South African Safari">
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </span>
                        <span class="rating-count">4.9 (87 reviews)</span>
                    </div>
                    <h3>Ultimate Safari Experience</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> Cape Town, Kruger National Park</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 9 days, 8 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Big Five Safari, Table Mountain, Wine Tasting</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$4,299</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>

            <div class="tour-card" data-continent="africa" data-country="egypt" data-duration="medium" data-budget="standard" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/egypt.jpg" alt="Egypt Pyramids Tour">
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </span>
                        <span class="rating-count">4.3 (145 reviews)</span>
                    </div>
                    <h3>Ancient Egypt Explorer</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> Cairo, Luxor, Aswan</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 7 days, 6 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Pyramids, Nile Cruise, Valley of Kings</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$1,799</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>

            <!-- North America Tours -->
            <div class="tour-card" data-continent="north-america" data-country="usa" data-duration="medium" data-budget="standard" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/newYork.webp" alt="New York City Tour">
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </span>
                        <span class="rating-count">4.1 (203 reviews)</span>
                    </div>
                    <h3>New York City Explorer</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> New York, USA</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 5 days, 4 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Statue of Liberty, Times Square, Broadway Show</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$1,699</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>

            <div class="tour-card" data-continent="north-america" data-country="canada" data-duration="long" data-budget="standard" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/canada.jpg" alt="Canadian Rockies">
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </span>
                        <span class="rating-count">4.6 (112 reviews)</span>
                    </div>
                    <h3>Canadian Rockies Adventure</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> Vancouver, Banff, Jasper</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 8 days, 7 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Lake Louise, Moraine Lake, Hiking Trails</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$2,499</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>

            <!-- South America Tours -->
            <div class="tour-card" data-continent="south-america" data-country="peru" data-duration="long" data-budget="standard" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/lima.jpg" alt="Machu Picchu Tour">
                    <div class="tour-badge">POPULAR</div>
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </span>
                        <span class="rating-count">4.8 (176 reviews)</span>
                    </div>
                    <h3>Inca Trail to Machu Picchu</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> Lima, Cusco, Machu Picchu</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 9 days, 8 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Inca Trail, Sacred Valley, Cusco Exploration</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$2,299</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>

            <!-- Australia Tours -->
            <div class="tour-card" data-continent="australia" data-country="australia" data-duration="long" data-budget="luxury" data-aos="fade-up">
                <div class="tour-image">
                    <img src="images/australia.jpeg" alt="Australia Coastal Tour">
                </div>
                <div class="tour-info">
                    <div class="tour-rating">
            <span class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </span>
                        <span class="rating-count">4.7 (98 reviews)</span>
                    </div>
                    <h3>Australian Coastal Explorer</h3>
                    <p class="tour-location"><i class="fas fa-map-marker-alt"></i> Sydney, Melbourne, Great Barrier Reef</p>
                    <ul class="tour-features">
                        <li><i class="fas fa-calendar-alt"></i> 14 days, 13 nights</li>
                        <li><i class="fas fa-plane"></i> Round-trip flight included</li>
                        <li><i class="fas fa-hiking"></i> Sydney Opera House, Great Barrier Reef, Wildlife Tours</li>
                    </ul>
                    <div class="tour-footer">
                        <div class="tour-price">
                            <span class="price">$4,899</span>
                            <span class="per-person">per person</span>
                        </div>
                        <a href="#" class="view-details">View Details</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- No Results Message -->
        <div id="noResults" class="no-results" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>No tours match your search criteria</h3>
            <p>Try adjusting your filters or search terms</p>
            <button id="resetFilters" class="reset-btn">Reset Filters</button>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <span class="page-dots">...</span>
            <button class="page-btn">10</button>
            <button class="page-next">Next <i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
</section>






<?php include 'footer.php'; ?>




<script>
    // Initialize AOS Animation
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({duration:1000, once:true});

        // Toggle menu
        document.getElementById("hamburger").addEventListener("click", () => {
            document.getElementById("navLinks").classList.toggle("show");
        });

        // Fixed header on scroll
        const mainHeader = document.getElementById("mainHeader");
        const headerHeight = mainHeader.offsetHeight;

        window.addEventListener("scroll", () => {
            if (window.scrollY > 100) {
                mainHeader.classList.add("fixed");
                document.body.style.paddingTop = headerHeight + "px";
            } else {
                mainHeader.classList.remove("fixed");
                document.body.style.paddingTop = 0;
            }
        });

        // Back to Top Button functionality
        const backToTopBtn = document.createElement('button');
        backToTopBtn.id = 'backToTop';
        backToTopBtn.innerHTML = '<i class="fa-solid fa-arrow-up"></i>';
        document.body.appendChild(backToTopBtn);

        window.addEventListener("scroll", () => {
            backToTopBtn.style.display = window.scrollY > 300 ? "block" : "none";
        });

        backToTopBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });

        // Modal functionality
        const modal = document.getElementById('authModal');
        const loginBtn = document.getElementById('loginBtn');
        const closeBtn = document.getElementsByClassName('close')[0];

        // Only attach if elements exist
        if (loginBtn) {
            loginBtn.addEventListener('click', function(e) {
                e.preventDefault();
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeModal();
            });
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                closeModal();
            }
        });

        // Close modal function
        function closeModal() {
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = ''; // Re-enable scrolling
            }
        }

        // Initialize auto-hiding alerts
        const alerts = document.querySelectorAll('.auth-alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('fade-out');
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });
    });

    // Tab functionality
    function openTab(evt, tabName) {
        // Declare variables
        let i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }
</script>






<!-- Back to Top Button (added via JavaScript) -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
<script src="js/custom.js"></script>
<script src="js/tours.js"></script>

</body>
</html>