<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Page Header -->
<header class="top-bar">
    <div class="container"></div>
    <span> <i class="fa-solid fa-location-dot "></i> Nablus </span>
    <span> <i class="fa-solid fa-envelope"> </i> pathfinder1738@protonmail.com</span>
    <span> <i class="fa-solid fa-clock"></i> Opening hour 08:00 AM </span>
</header>
<style>
    /* Google Font */
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@600&display=swap');

    .logo {
        display: flex;
        align-items: center;
        padding: 10px 20px;
    }

    .logo-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        font-family: 'Quicksand', sans-serif;
        font-size: 1.8rem;
        font-weight: bold;
        color: #3dbb91;
        transition: color 0.3s ease;
    }

    .logo-link:hover {
        color: #3dbb91;
    }

    .logo i {
        color: #3dbb91;
        font-size: 2rem;
        margin-right: 10px;
        transition: transform 0.3s ease;
    }

    .logo-link:hover i {
        transform: rotate(20deg);
    }

    .logo-text .highlight {
        color: #3dbb91;
    }

</style>
<nav class="navbar"
     id="mainHeader">
    <div class="container nav-container">
        <!-- Logo Section -->
        <div class="logo">
            <a href="index.php" class="logo-link">
                <i class="fa-solid fa-compass"></i>
                <span class="logo-text">Path<span class="highlight">Finder</span></span>
            </a>
        </div>


        <ul class="nav-links" id="navLinks">
            <li> <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="active"' : ''; ?>> Home </a></li>
            <li> <a href="ThingsToDo.php" <?php echo basename($_SERVER['PHP_SELF']) == 'ThingsToDo.php' ? 'class="active"' : ''; ?>> Things To Do </a></li>
            <li> <a href="Tours.php" <?php echo basename($_SERVER['PHP_SELF']) == 'Tours.php' ? 'class="active"' : ''; ?>> Tours </a></li>
            <li> <a href="CustomTour.php" <?php echo basename($_SERVER['PHP_SELF']) == 'CustomTour.php' ? 'class="active"' : ''; ?>> Custom Tour </a></li>
            <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                <li> <a href="admin.php" <?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'class="active"' : ''; ?>> For Admin </a></li>
            <?php endif; ?>
            <li> <a href="#">  </a></li>
            <li> <a href="#">  </a></li>
            <li> <a href="#">  </a></li>
            <li> <a href="#">  </a></li>
            <li> <a href="#">  </a></li>
            <li> <a href="#">  </a></li>
            <li> <a href="#">  </a></li>
            <li> <a href="#">  </a></li>
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <!-- User is logged in -->
                <li class="user-menu">
                    <div class="username-display">
                        <i class="fa-solid fa-user"></i>
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                        <i class="fa-solid fa-caret-down" style="margin-left: 5px;"></i>
                    </div>
                    <div class="user-menu-content">
                        <a href="profile.php">My Profile</a>
                        <a href="my_bookings.php">My Bookings</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </li>
            <?php else: ?>
                <!-- User is not logged in -->
                <li> <a href="#" class="btn" id="loginBtn"> Start Booking </a></li>
            <?php endif; ?>
        </ul>
        <div class="hamburger" id="hamburger"> &#9776;</div> <!--The Three lines for menu -->
    </div>
</nav>