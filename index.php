<?php
// Start the session
session_start();
     ///////tdel y ousef al thani
// Set page titless
$pageTitle = "Home";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-imgs">
        <img src="images/doodleplane1.png" alt="plane left" class="round-img left">
        <img src="images/doodleHotAirBaloon.png" alt="plane left" class="round-img right">
    </div>
    <div class="hero-content">
        <h3 class="subtitle" data-aos="fade-down">Welcome to Pathfinder</h3>  <!-- aos = animation on scroll form javascript library -->
        <h1 class="title" data-aos="fade-down">Adventures & <br>Memories Await </h1>
        <p data-aos="fade-down"> Explore the world with confidence — your next adventure begins here. </p>
    </div>
    <div class="hero-back"></div>
</section>

<!-- tour section -->
<section class="tour-section">
  <h2 class ="subtitle" data-aos="fade-up"> Wonderful Places For You  </h2>
  <h1 class="title" data-aos="fade-up"> Popular Destination</h1>
  <div class="swiper tourSwiper" data-aos="fade-up">
    <div class="swiper-wrapper">

      <div class="swiper-slide card">
        <img src="images/parisCard.jpg" alt="slide 1">
        <h3> Paris </h3>
        <p> Paris is the capital and most populated city of France.</p>
      </div>
      <div class="swiper-slide card">
        <img src="images/madridCard.jpg" alt="slide 2">
        <h3> Madrid </h3>
        <p> Madrid, Spain's central capital, is a city of elegant boulevards.</p>
      </div>
      <div class="swiper-slide card">
        <img src="images/dubaiCard.avif" alt="slide 3">
        <h3> Dubai </h3>
        <p> Dubai is well known for luxury shopping and a lively nightlife scene.</p>
      </div>
      <div class="swiper-slide card">
        <img src="images/newYorkCard.avif" alt="slide 4">
        <h3> New York </h3>
        <p> New York City (NYC), is the most populous city in the United States.</p>
      </div>
      <div class="swiper-slide card">
        <img src="images/GizaCard.webp" alt="slide 5">
        <h3> Giza </h3>
        <p> Giza is home to iconic Egyptian monuments, including 3 tall pyramids.</p>
      </div>
      <div class="swiper-slide card">
        <img src="images/romeCard.jpg" alt="slide 6">
        <h3> Rome </h3>
        <p> Rome is the capital city and most populated city of Italy.</p>
      </div>
      <div class="swiper-slide card">
        <img src="images/londonCard.jpg" alt="slide 7">
        <h3> London </h3>
        <p> London is a historic financial district, home to both the Stock Exchange and the Bank of England.</p>
      </div>
      <div class="swiper-slide card">
        <img src="images/athensCard.webp" alt="slide 8">
        <h3> Athens  </h3>
        <p> Athens is the capital of Greece. It was also at the heart of Ancient Greece.</p>
      </div>


    </div>
    <div class="swiper-pagination"></div>
  </div>

</section>
<section class="travel-story">
  <div class="container">
    <div class="story-left" data-aos="fade-left">
      <div class="story-grid">
        <img src="images/fam.jpg" alt="Family traveling together">
      </div>
    </div>
    <div class="story-right" data-aos="fade-right">
      <p class="subtitle">Let's Go Together</p>
      <h2 class="title" data-aos="fade-up">Start Your Journey Here</h2>
      <p class="desc">
        Let the spirit of exploration forever fuel your adventures, crafting a tapestry of cherished memories that never cease to grow.
      </p>

      <div class="feature-box">
        <div >
          <i class="fa-solid fa-globe"></i>
        </div>
        <div>
          <h4>Professional Guide</h4>
          <p>Our experienced guides offer local expertise, personalized itineraries, and insider knowledge to ensure unforgettable, authentic travel experiences tailored just for you.</p>
        </div>
      </div>

      <div class="feature-box">
        <div >
          <i class="fa-solid fa-shield"></i>
        </div>
        <div>
          <h4>Safety First</h4>
          <p>Travel with peace of mind as our guides prioritize your safety while navigating unfamiliar territories, handling logistics, and addressing any concerns throughout your journey.</p>
        </div>
      </div>

<!--      <a href="#" class="learn-btn">Learn More</a>   &lt;!&ndash; a page that goes to the guidelines&ndash;&gt;>-->
    </div>
  </div>
</section>
<!--  top tours changed  -->

<section class="destination-section">
  <p class="subtitle" data-aos="fade-down"> Top Tours</p>
  <h2 class="title" data-aos="fade-down">Popular Tours</h2>
  <div class="swiper mySwiper" data-aos="fade-down">
    <div class="swiper-wrapper">

      <!-- Slide 1 -->
      <div class="swiper-slide destination-card">
        <img src="images/europeTour.jpg" alt="Europe Tour">
<!--        <a href="Tours.php"> Tours </a>-->
<!--        <a href="Tours.php" class="view-details">View Details</a>-->
        <h3>Europe</h3>
        <p>Travel All Across Europe And See Its Famous Landmarks.</p>
      </div>

      <!-- Slide 2 -->
      <div class="swiper-slide destination-card">
        <img src="images/asiaTour.jpg" alt="Asia Tour">
        <h3>Asia</h3>
        <p>Travel All Across Asia And See Its Famous Landmarks.</p>
      </div>

      <!-- Slide 3 -->
      <div class="swiper-slide destination-card">
        <img src="images/austTour.webp" alt="Australia Tour">
        <h3>Australia</h3>
        <p>Travel All Across Australia And See Its Famous Landmarks.</p>
      </div>

      <!-- Slide 4 -->
      <div class="swiper-slide destination-card">
        <img src="images/aficaTour.jpeg" alt="Africa Tour">
        <h3>Africa</h3>
        <p>Travel All Across Africa And See Its Famous Landmarks.</p>
      </div>

      <!-- Slide 5 -->
      <div class="swiper-slide destination-card">
        <img src="images/northAmTour.jpg" alt="North America Tour">
        <h3>North America</h3>
        <p>Travel All Across North America And See Its Famous Landmarks.</p>
      </div>

      <!-- Slide 6 -->
      <div class="swiper-slide destination-card">
        <img src="images/southAmTour.jpg" alt="South America Tour">
        <h3>South America</h3>
        <p>Travel All Across South America And See Its Famous Landmarks.</p>
      </div>
    </div>

    <!-- Add pagination -->
    <div class="swiper-pagination"></div>
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



<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<!-- scroll animation js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
<script src="js/custom.js"></script>



</body>
</html>