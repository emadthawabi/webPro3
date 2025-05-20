<?php
// Start the session
session_start();

// Set page title
$pageTitle = "ThingsToDo";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>

<!-- Enhanced Hero Section -->
<section class="things-to-do-hero">
    <!-- Animated background shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="hero-content" data-aos="zoom-in">
        <h3 class="subtitle" data-aos="fade-down" data-aos-delay="200">Explore Destinations</h3>
        <h1 class="title" data-aos="fade-down" data-aos-delay="400">Find Amazing Things To Do</h1>
        <p data-aos="fade-up" data-aos-delay="600">Discover unique experiences and attractions tailored to your next destination</p>
        <a href="#destination-filter" class="hero-cta" data-aos="fade-up" data-aos-delay="800">Start Exploring</a>
    </div>
</section>

<!-- Search Filters Section -->
<section id="destination-filter" class="destination-filter">
    <div class="container">
        <div class="filter-wrapper" data-aos="fade-up">
            <h2>Where would you like to explore?</h2>
            <div class="filter-selectors">
                <div class="selector-group">
                    <label for="continent-select">Continent</label>
                    <select id="continent-select">
                        <option value="">Select a continent</option>
                        <option value="europe">Europe</option>
                        <option value="asia">Asia</option>
                        <option value="africa">Africa</option>
                        <option value="north_america">North America</option>
                        <option value="south_america">South America</option>
                        <!--                        <option value="oceania">Oceania</option>-->
                    </select>
                </div>

                <div class="selector-group">
                    <label for="country-select">Country</label>
                    <select id="country-select" disabled>
                        <option value="">Select a country</option>
                    </select>
                </div>

                <div class="selector-group">
                    <label for="city-select">City</label>
                    <select id="city-select" disabled>
                        <option value="">Select a city</option>
                    </select>
                </div>

                <button id="search-btn" class="search-button" disabled>Find Attractions</button>
            </div>
        </div>
    </div>
</section>

<!-- Rest of the content remains the same -->
<!-- Results Section -->
<section class="attractions-results">
    <div class="container">
        <div id="results-wrapper" class="results-wrapper hidden">
            <h2 id="results-title" data-aos="fade-up">Top Attractions in <span id="city-name">City</span></h2>
            <p class="subtitle" id="results-subtitle" data-aos="fade-up">Discover amazing experiences waiting for you</p>

            <div id="results-grid" class="results-grid" data-aos="fade-up">
                <!-- Attractions will be populated here via JavaScript -->
            </div>

            <div id="no-results" >
                <!--                <i class="fa-solid fa-map-pin"></i>-->
                <!--                <h3>No attractions found</h3>-->
                <!--                <p>Please try a different selection</p>-->
            </div>
        </div>

        <div id="initial-state" class="initial-state">
            <i class="fa-solid fa-earth-americas"></i>
            <h3>Select a location above to discover attractions</h3>
            <p>Choose a continent, country, and city to get started</p>
        </div>
    </div>
</section>

<!-- Travel Tips Section -->
<section class="travel-tips">
    <div class="container">
        <div class="tips-content" data-aos="fade-right">
            <h2 class="title">Travel Smarter</h2>
            <p>Get the most out of your journey with our expert travel tips</p>

            <div class="tips-list">
                <div class="tip-item">
                    <i class="fa-solid fa-calendar-check"></i>
                    <div>
                        <h4>Plan Ahead</h4>
                        <p>Book popular attractions in advance to avoid long queues and sold-out experiences.</p>
                    </div>
                </div>

                <div class="tip-item">
                    <i class="fa-solid fa-clock"></i>
                    <div>
                        <h4>Time Your Visits</h4>
                        <p>Visit major attractions early in the morning or late afternoon to avoid crowds.</p>
                    </div>
                </div>

                <div class="tip-item">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <div>
                        <h4>Group by Location</h4>
                        <p>Plan your itinerary to explore attractions that are close to each other on the same day.</p>
                    </div>
                </div>
            </div>

            <!--            <a href="#" class="learn-btn">More Tips</a>-->
        </div>

        <div class="tips-image" data-aos="fade-left">
            <img src="images/travel-tips.jpg" alt="Travel Tips">

        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script>
    // Initialize AOS Animation with modified settings for hero section
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 1200,
            once: false,
            mirror: true,
            offset: 120
        });

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

        // Smooth scroll for hero CTA button
        document.querySelector('.hero-cta').addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
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

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<!-- Scroll animation js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="js/custom.js"></script>
<script src="js/ThingsToDo.js"></script>
</body>
</html>