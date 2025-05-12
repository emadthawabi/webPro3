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
    <span> <i class="fa-solid fa-envelope"> </i> Pathinder33@gmail.com</span>
    <span> <i class="fa-solid fa-clock"></i> Opening hour 08:00 AM </span>
</header>

<nav class="navbar"
     id="mainHeader">
    <div class="container nav-container">
        <div class="logo">
            <i class="fa-solid fa-globe">  </i>   &nbsp; PathFinder    <!--might change for a better logo-->
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
                        <a href="my-bookings.php">My Bookings</a>
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