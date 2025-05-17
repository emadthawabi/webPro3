<?php
// Start the session
session_start();

// Set page title
$pageTitle = "Admin Dashboard";

// Check if user is logged in (placeholder - implement actual auth)
$isLoggedIn = true; // This would normally check session data

// Redirect if not logged in
if (!$isLoggedIn) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/adminstyles.css">
    <title>Admin</title>
</head>
<body>
<?php include 'header.php'; ?>

<!-- Admin Hero Section -->
<section class="admin-hero">
    <div class="container">
        <h1 data-aos="fade-down">Admin Dashboard</h1>
        <p data-aos="fade-up">Manage tours, flights, hotels, destinations, and customers</p>
    </div>
</section>

<!-- Admin Dashboard Section -->
<section class="admin-dashboard-section">
    <div class="container">
        <div class="admin-container" data-aos="fade-up">
            <!-- Admin Navigation Tabs -->
            <div class="admin-tabs">
                <button class="tab-btn active" data-tab="tours">
                    <i class="fas fa-hiking"></i> Tours
                </button>
                <button class="tab-btn" data-tab="flights">
                    <i class="fas fa-plane"></i> Flights
                </button>
                <button class="tab-btn" data-tab="hotels">
                    <i class="fas fa-hotel"></i> Hotels
                </button>
                <button class="tab-btn" data-tab="destinations">
                    <i class="fas fa-map-marker-alt"></i> Destinations
                </button>
                <button class="tab-btn" data-tab="customers">
                    <i class="fas fa-users"></i> Customers
                </button>
            </div>

            <!-- Admin Content Areas -->
            <div class="admin-content">
                <!-- Tours Tab Content -->
                <div class="tab-content active" id="tours-content">
                    <?php require 'components/tours-management.php'; ?>
                </div>

                <!-- Flights Tab Content -->
                <div class="tab-content" id="flights-content">
                    <?php require 'components/flights-management.php'; ?>
                </div>

                <!-- Hotels Tab Content -->
                <div class="tab-content" id="hotels-content">
                    <?php require 'components/hotels-management.php'; ?>
                </div>

                <!-- Destinations Tab Content -->
                <div class="tab-content" id="destinations-content">
                    <?php require 'components/destinations-management.php'; ?>
                </div>

                <!-- Customers Tab Content -->
                <div class="tab-content" id="customers-content">
                    <?php require 'components/customers-management.php'; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Notification Toast -->
<div id="notification-toast" class="notification-toast">
    <div class="notification-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="notification-message">Operation completed successfully!</div>
    <button class="notification-close"><i class="fas fa-times"></i></button>
</div>

<?php /*include 'footer.php'; */?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="js/custom.js"></script>
<script src="js/admin.js"></script>
</body>
</html>