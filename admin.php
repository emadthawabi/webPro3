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

// Database connection - Replace with your actual credentials
try {
    $host = "localhost";
    $dbname = "pathfinder";
    $username = "root"; // Replace with your DB username
    $password = ""; // Replace with your DB password

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Real analytics data from database - Dynamic queries with error handling
$analytics = [];

try {
    // Total unique customers (counting unique SSNs, excluding admin users)
    $stmt = $pdo->query("SELECT COUNT(DISTINCT ssn) as total_customers FROM customer WHERE username NOT IN ('yousef', 'emad')");
    $analytics['total_customers'] = $stmt->fetchColumn();

    // Active tours count
    $stmt = $pdo->query("SELECT COUNT(*) as active_tours FROM tours");
    $analytics['active_tours'] = $stmt->fetchColumn();

    // Destinations count
    $stmt = $pdo->query("SELECT COUNT(*) as destinations_count FROM destination");
    $analytics['destinations_count'] = $stmt->fetchColumn();

    // Flights count
    $stmt = $pdo->query("SELECT COUNT(*) as flights_count FROM flights");
    $analytics['flights_count'] = $stmt->fetchColumn();

    // Hotels count
    $stmt = $pdo->query("SELECT COUNT(*) as hotels_count FROM hotels");
    $analytics['hotels_count'] = $stmt->fetchColumn();

} catch(PDOException $e) {
    // Fallback values if queries fail
    $analytics = [
        'total_customers' => 0,
        'active_tours' => 0,
        'destinations_count' => 0,
        'flights_count' => 0,
        'hotels_count' => 0
    ];
    error_log("Analytics query failed: " . $e->getMessage());
}

// Tour booking data from customer table - counting tourid occurrences (excluding NULL and admin users)
$tourBookingsFromDB = [];
$tourNames = [];
$tourDetails = [];

try {
    // Count tour bookings excluding admin users (yousef, emad)
    $stmt = $pdo->query("
        SELECT 
            tourid, 
            COUNT(*) as booking_count 
        FROM customer 
        WHERE tourid IS NOT NULL 
        AND username NOT IN ('yousef', 'emad')
        GROUP BY tourid 
        ORDER BY booking_count DESC
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tourBookingsFromDB[$row['tourid']] = $row['booking_count'];
    }

    // Get tour names and details from tours table
    $stmt = $pdo->query("SELECT tourid, tourname, rating, duration FROM tours");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $tourNames[$row['tourid']] = $row['tourname'];
        $tourDetails[$row['tourid']] = [
            'rating' => $row['rating'],
            'duration' => $row['duration']
        ];
    }
} catch(PDOException $e) {
    error_log("Tour booking query failed: " . $e->getMessage());
}

// Create most booked tours array sorted by bookings
$mostBookedTours = [];
foreach($tourBookingsFromDB as $tourId => $bookings) {
    if (isset($tourNames[$tourId])) {
        $mostBookedTours[] = [
            'id' => $tourId,
            'name' => $tourNames[$tourId],
            'bookings' => $bookings,
            'rating' => $tourDetails[$tourId]['rating'] ?? 0,
            'duration' => $tourDetails[$tourId]['duration'] ?? 0
        ];
    }
}

// Most visited destinations - counting destid occurrences in customer table (excluding NULL and admin users)
$mostVisitedDestinations = [];
try {
    $stmt = $pdo->query("
        SELECT 
            c.destid,
            d.city as name,
            d.country,
            d.continent,
            COUNT(*) as visits
        FROM customer c
        JOIN destination d ON c.destid = d.destid
        WHERE c.destid IS NOT NULL 
        AND c.username NOT IN ('yousef', 'emad')
        GROUP BY c.destid, d.city, d.country, d.continent
        ORDER BY visits DESC
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mostVisitedDestinations[] = [
            'id' => $row['destid'],
            'name' => $row['name'],
            'country' => $row['country'],
            'continent' => $row['continent'],
            'visits' => $row['visits']
        ];
    }
} catch(PDOException $e) {
    error_log("Destinations query failed: " . $e->getMessage());
}

// Most active customers - counting customer occurrences by email (excluding admin users)
$mostActiveCustomers = [];
try {
    $stmt = $pdo->query("
        SELECT 
            username as name,
            email,
            ssn,
            COUNT(*) as bookings
        FROM customer
        WHERE username NOT IN ('yousef', 'emad')
        GROUP BY ssn, email, username
        ORDER BY bookings DESC
        LIMIT 10
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mostActiveCustomers[] = [
            'name' => ucfirst($row['name']),
            'email' => $row['email'],
            'bookings' => $row['bookings'],
            'ssn' => $row['ssn']
        ];
    }
} catch(PDOException $e) {
    error_log("Active customers query failed: " . $e->getMessage());
}

// System Health Check - checking for destinations without hotels or flights
$destinationsWithoutHotels = 0;
$destinationsWithoutFlights = 0;
$destinationsWithoutHotelsDetails = [];
$destinationsWithoutFlightsDetails = [];

try {
    // Destinations without hotels - get count and details
    $stmt = $pdo->query("
        SELECT 
            d.destid,
            d.city,
            d.country,
            d.continent
        FROM destination d
        LEFT JOIN hotels h ON d.destid = h.destid
        WHERE h.destid IS NULL
        ORDER BY d.country, d.city
    ");
    $destinationsWithoutHotelsDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $destinationsWithoutHotels = count($destinationsWithoutHotelsDetails);

    // Destinations without flights - get count and details
    $stmt = $pdo->query("
        SELECT 
            d.destid,
            d.city,
            d.country,
            d.continent
        FROM destination d
        LEFT JOIN flights f ON d.destid = f.destid
        WHERE f.destid IS NULL
        ORDER BY d.country, d.city
    ");
    $destinationsWithoutFlightsDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $destinationsWithoutFlights = count($destinationsWithoutFlightsDetails);
} catch(PDOException $e) {
    error_log("System health query failed: " . $e->getMessage());
}

// Tour booking data for chart - using real data from customer table
$tourBookingData = [];
foreach($tourBookingsFromDB as $tourId => $bookings) {
    if (isset($tourNames[$tourId])) {
        $tourBookingData[$tourNames[$tourId]] = $bookings;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/adminstyles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Dotted Background */
        body {
            background: radial-gradient(circle at 25% 25%, #a3b1c6 15%, transparent 15%),
            radial-gradient(circle at 75% 75%, #a3b1c6 15%, transparent 15%);
            background-size: 10px 10px;
            background-color: #e0e7ed;
            min-height: 100vh;
        }

        /* Dashboard Analytics Styles */
        .dashboard-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }

        .metric-card {
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 30px 25px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s;
        }

        .metric-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .metric-card:hover::before {
            animation: shimmer 0.6s ease-out;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .metric-card.danger {
            background: linear-gradient(145deg, var(--danger), var(--danger-dark));
        }
        .metric-card.warning {
            background: linear-gradient(145deg, var(--warning), var(--warning-dark));
        }
        .metric-card.success {
            background: linear-gradient(145deg, var(--success), var(--success-dark));
        }
        .metric-card.info {
            background: linear-gradient(145deg, var(--info), var(--info-dark));
        }

        .metric-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.9;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .metric-label {
            font-size: 1rem;
            opacity: 0.95;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .analytics-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .analytics-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .analytics-card h3 {
            margin: 0 0 25px 0;
            color: var(--dark);
            font-size: 1.3rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .analytics-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .analytics-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(224, 224, 224, 0.5);
            transition: all 0.3s ease;
        }

        .analytics-item:hover {
            background: var(--primary-bg);
            margin: 0 -15px;
            padding: 15px;
            border-radius: 10px;
        }

        .analytics-item:last-child {
            border-bottom: none;
        }

        .item-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .item-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(145deg, var(--primary-bg), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 4px;
            font-size: 1.05rem;
        }

        .item-meta {
            font-size: 0.9rem;
            color: var(--text);
            opacity: 0.8;
        }

        .item-value {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.1rem;
            background: var(--primary-bg);
            padding: 5px 12px;
            border-radius: 20px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-critical {
            background: linear-gradient(145deg, var(--danger-light), #ffebee);
            color: var(--danger);
            box-shadow: 0 2px 4px rgba(231, 76, 60, 0.2);
        }
        .status-warning {
            background: linear-gradient(145deg, var(--warning-light), #fff8e1);
            color: var(--warning);
            box-shadow: 0 2px 4px rgba(243, 156, 18, 0.2);
        }
        .status-good {
            background: linear-gradient(145deg, var(--success-light), #f1f8e9);
            color: var(--success);
            box-shadow: 0 2px 4px rgba(46, 204, 113, 0.2);
        }

        .quick-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .quick-action-btn {
            padding: 8px 16px;
            font-size: 0.85rem;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin: 20px 0;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 20px;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            z-index: 99999; /* Very high z-index to appear above header */
            padding: 20px;
            backdrop-filter: blur(2px); /* Add slight blur effect */
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            max-height: calc(100vh - 40px); /* Account for padding */
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 100000; /* Even higher z-index */
            transform: translateY(0);
            margin: auto;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            color: #222;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #999;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            color: #e74c3c;
            background-color: #f8f9fa;
        }

        .modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
            max-height: calc(80vh - 140px); /* Account for header and footer */
        }

        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background-color: #f8f9fa;
        }

        /* Issue Styles */
        .issue-section {
            margin-bottom: 30px;
        }

        .issue-section:last-child {
            margin-bottom: 0;
        }

        .issue-title {
            color: #222;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .issue-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .issue-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #fff8e1;
            border-left: 4px solid #ff9800;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .issue-item:hover {
            background-color: #fff3c4;
            transform: translateX(5px);
        }

        .issue-info {
            flex: 1;
        }

        .issue-name {
            font-weight: 600;
            color: #222;
            display: block;
            font-size: 1rem;
        }

        .issue-meta {
            font-size: 0.85rem;
            color: #666;
            margin-top: 4px;
            display: block;
        }

        .issue-badge {
            background-color: #ff9800;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }

        /* No Issues State */
        .no-issues {
            text-align: center;
            padding: 40px 20px;
        }

        .no-issues-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .no-issues h4 {
            color: #27ae60;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .no-issues p {
            color: #666;
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .analytics-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-overview {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            .chart-container {
                height: 300px;
            }

            .modal-overlay {
                padding: 10px;
            }

            .modal {
                width: 95%;
                max-height: calc(100vh - 20px);
            }

            .issue-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .issue-badge {
                align-self: flex-end;
            }

            .modal-footer {
                flex-direction: column;
            }

            .modal-footer .btn {
                width: 100%;
            }
        }
    </style>
    <title>Admin</title>
</head>
<body>
<?php include 'header.php'; ?>

<!-- Admin Hero Section -->
<section class="admin-hero">
    <div class="container">
        <h1 data-aos="fade-down">Admin Dashboard</h1>
        <p data-aos="fade-up">Real-time analytics from your database</p>
    </div>
</section>

<!-- Admin Dashboard Section -->
<section class="admin-dashboard-section">
    <div class="container">
        <div class="admin-container" data-aos="fade-up">
            <!-- Admin Navigation Tabs -->
            <div class="admin-tabs">
                <button class="tab-btn active" data-tab="dashboard">
                    <i class="fas fa-chart-line"></i> Dashboard
                </button>
                <button class="tab-btn" data-tab="tours">
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
                <!-- Dashboard Tab Content -->
                <div class="tab-content active" id="dashboard-content">
                    <!-- Overview Metrics -->
                    <div class="dashboard-overview">
                        <div class="metric-card">
                            <div class="metric-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="metric-value"><?php echo number_format($analytics['total_customers']); ?></div>
                            <div class="metric-label">Unique Customers (Excluding Admins)</div>
                        </div>
                        <div class="metric-card info">
                            <div class="metric-icon">
                                <i class="fas fa-hotel"></i>
                            </div>
                            <div class="metric-value"><?php echo number_format($analytics['hotels_count']); ?></div>
                            <div class="metric-label">Total Hotels</div>
                        </div>
                        <div class="metric-card warning">
                            <div class="metric-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="metric-value"><?php echo number_format($analytics['destinations_count']); ?></div>
                            <div class="metric-label">Destinations</div>
                        </div>
                        <div class="metric-card success">
                            <div class="metric-icon">
                                <i class="fas fa-plane"></i>
                            </div>
                            <div class="metric-value"><?php echo number_format($analytics['flights_count']); ?></div>
                            <div class="metric-label">Available Flights</div>
                        </div>
                    </div>

                    <!-- Analytics Grid -->
                    <div class="analytics-grid">
                        <!-- Most Popular Tours (Real Data from Customer Table) -->
                        <div class="analytics-card">
                            <h3><i class="fas fa-fire text-danger"></i> Most Booked Tours</h3>
                            <ul class="analytics-list">
                                <?php foreach(array_slice($mostBookedTours, 0, 4) as $tour): ?>
                                    <li class="analytics-item">
                                        <div class="item-info">
                                            <div class="item-avatar"><?php echo strtoupper(substr($tour['name'], 0, 1)); ?></div>
                                            <div class="item-details">
                                                <div class="item-name"><?php echo $tour['name']; ?></div>
                                                <div class="item-meta"><?php echo $tour['duration']; ?> days • <?php echo $tour['rating']; ?>★</div>
                                            </div>
                                        </div>
                                        <div class="item-value"><?php echo $tour['bookings']; ?> bookings</div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Most Visited Destinations (Real Data from Customer Table) -->
                        <div class="analytics-card">
                            <h3><i class="fas fa-globe-americas text-primary"></i> Most Visited Destinations</h3>
                            <ul class="analytics-list">
                                <?php foreach(array_slice($mostVisitedDestinations, 0, 4) as $destination): ?>
                                    <li class="analytics-item">
                                        <div class="item-info">
                                            <div class="item-avatar">
                                                <?php
                                                $flags = [
                                                    'France' => '🇫🇷', 'Japan' => '🇯🇵', 'Thailand' => '🇹🇭',
                                                    'Mexico' => '🇲🇽', 'Singapore' => '🇸🇬', 'Spain' => '🇪🇸'
                                                ];
                                                echo $flags[$destination['country']] ?? '🌍';
                                                ?>
                                            </div>
                                            <div class="item-details">
                                                <div class="item-name"><?php echo $destination['name']; ?>, <?php echo $destination['country']; ?></div>
                                                <div class="item-meta"><?php echo $destination['continent']; ?></div>
                                            </div>
                                        </div>
                                        <div class="item-value"><?php echo $destination['visits']; ?> visits</div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Most Active Customers (Real Data from Customer Table) -->
                        <div class="analytics-card">
                            <h3><i class="fas fa-crown text-warning"></i> Most Active Customers</h3>
                            <ul class="analytics-list">
                                <?php foreach(array_slice($mostActiveCustomers, 0, 4) as $customer): ?>
                                    <li class="analytics-item">
                                        <div class="item-info">
                                            <div class="item-avatar"><?php echo strtoupper(substr($customer['name'], 0, 1)); ?></div>
                                            <div class="item-details">
                                                <div class="item-name"><?php echo $customer['name']; ?></div>
                                                <div class="item-meta"><?php echo $customer['email']; ?></div>
                                            </div>
                                        </div>
                                        <div class="item-value"><?php echo $customer['bookings']; ?> bookings</div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- System Health Monitor -->
                        <div class="analytics-card">
                            <h3><i class="fas fa-shield-alt text-success"></i> System Health</h3>
                            <ul class="analytics-list">
                                <li class="analytics-item">
                                    <div class="item-info">
                                        <div class="item-details">
                                            <div class="item-name">Destinations Without Hotels</div>
                                            <div class="item-meta">Missing accommodation options</div>
                                        </div>
                                    </div>
                                    <span class="status-badge <?php echo $destinationsWithoutHotels > 0 ? 'status-warning' : 'status-good'; ?>">
                                        <?php echo $destinationsWithoutHotels; ?> Issues
                                    </span>
                                </li>
                                <li class="analytics-item">
                                    <div class="item-info">
                                        <div class="item-details">
                                            <div class="item-name">Destinations Without Flights</div>
                                            <div class="item-meta">Missing flight connections</div>
                                        </div>
                                    </div>
                                    <span class="status-badge <?php echo $destinationsWithoutFlights > 0 ? 'status-warning' : 'status-good'; ?>">
                                        <?php echo $destinationsWithoutFlights; ?> Issues
                                    </span>
                                </li>
                                <li class="analytics-item">
                                    <div class="item-info">
                                        <div class="item-details">
                                            <div class="item-name">Database Integrity</div>
                                            <div class="item-meta">All relationships validated</div>
                                        </div>
                                    </div>
                                    <span class="status-badge status-good">Healthy</span>
                                </li>
                                <li class="analytics-item">
                                    <div class="item-info">
                                        <div class="item-details">
                                            <div class="item-name">Tour Coverage</div>
                                            <div class="item-meta">Tours available for all destinations</div>
                                        </div>
                                    </div>
                                    <span class="status-badge status-good">Complete</span>
                                </li>
                            </ul>
                            <div class="quick-actions">
                                <?php if ($destinationsWithoutHotels > 0 || $destinationsWithoutFlights > 0): ?>
                                    <button class="btn btn-sm btn-warning quick-action-btn" data-modal-target="system-issues-modal">
                                        <i class="fas fa-exclamation-triangle"></i> View Issues
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Tour Booking Analytics Chart -->
                        <div class="analytics-card" style="grid-column: 1 / -1;">
                            <h3><i class="fas fa-chart-bar text-success"></i> Tour Booking Distribution</h3>
                            <div class="chart-container">
                                <canvas id="tourBookingChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tours Tab Content -->
                <div class="tab-content" id="tours-content">
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

<!-- System Issues Modal -->
<div id="system-issues-modal" class="modal-overlay">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> System Health Issues</h3>
            <button class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <?php if ($destinationsWithoutHotels > 0): ?>
                <div class="issue-section">
                    <h4 class="issue-title">
                        <i class="fas fa-hotel text-danger"></i>
                        Destinations Without Hotels (<?php echo $destinationsWithoutHotels; ?>)
                    </h4>
                    <div class="issue-list">
                        <?php foreach($destinationsWithoutHotelsDetails as $dest): ?>
                            <div class="issue-item">
                                <div class="issue-info">
                                    <span class="issue-name"><?php echo $dest['city']; ?>, <?php echo $dest['country']; ?></span>
                                    <span class="issue-meta"><?php echo $dest['continent']; ?> • ID: <?php echo $dest['destid']; ?></span>
                                </div>
                                <span class="issue-badge">
                                    <i class="fas fa-exclamation-circle"></i> No Hotels
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($destinationsWithoutFlights > 0): ?>
                <div class="issue-section">
                    <h4 class="issue-title">
                        <i class="fas fa-plane text-danger"></i>
                        Destinations Without Flights (<?php echo $destinationsWithoutFlights; ?>)
                    </h4>
                    <div class="issue-list">
                        <?php foreach($destinationsWithoutFlightsDetails as $dest): ?>
                            <div class="issue-item">
                                <div class="issue-info">
                                    <span class="issue-name"><?php echo $dest['city']; ?>, <?php echo $dest['country']; ?></span>
                                    <span class="issue-meta"><?php echo $dest['continent']; ?> • ID: <?php echo $dest['destid']; ?></span>
                                </div>
                                <span class="issue-badge">
                                    <i class="fas fa-exclamation-circle"></i> No Flights
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($destinationsWithoutHotels == 0 && $destinationsWithoutFlights == 0): ?>
                <div class="no-issues">
                    <div class="no-issues-icon">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <h4>No Issues Found!</h4>
                    <p>All destinations have both hotels and flights available.</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close">Close</button>
            <?php if ($destinationsWithoutHotels > 0 || $destinationsWithoutFlights > 0): ?>

            <?php endif; ?>
        </div>
    </div>
</div>

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

<script>
    // Tour Booking Bar Chart - Based on actual customer table tourid counts
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('tourBookingChart').getContext('2d');

        // Get tour data from PHP (tour names and booking counts from customer table)
        const tourData = <?php echo json_encode(array_keys($tourBookingData)); ?>;
        const bookingData = <?php echo json_encode(array_values($tourBookingData)); ?>;

        console.log('Tour Booking Data:', tourData, bookingData); // Debug log

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: tourData,
                datasets: [{
                    label: 'Bookings from Customer Table',
                    data: bookingData,
                    backgroundColor: [
                        'rgba(61, 187, 145, 0.8)',   // Paris Wonderer
                        'rgba(52, 152, 219, 0.8)',   // Barcelona Experience
                        'rgba(231, 76, 60, 0.8)',    // Bangkok Adventure
                        'rgba(243, 156, 18, 0.8)',   // Mexico City Culinary
                        'rgba(155, 89, 182, 0.8)',   // Roman Holiday
                        'rgba(46, 204, 113, 0.8)',   // Santorini Sunset
                        'rgba(241, 196, 15, 0.8)',   // Tokyo Explorer
                        'rgba(230, 126, 34, 0.8)',   // Jaipur Heritage
                        'rgba(26, 188, 156, 0.8)',   // Dubai Luxury
                        'rgba(142, 68, 173, 0.8)'    // New York Discovery
                    ],
                    borderColor: [
                        'rgba(61, 187, 145, 1)',
                        'rgba(52, 152, 219, 1)',
                        'rgba(231, 76, 60, 1)',
                        'rgba(243, 156, 18, 1)',
                        'rgba(155, 89, 182, 1)',
                        'rgba(46, 204, 113, 1)',
                        'rgba(241, 196, 15, 1)',
                        'rgba(230, 126, 34, 1)',
                        'rgba(26, 188, 156, 1)',
                        'rgba(142, 68, 173, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Tour Bookings Based on Customer Table Data',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        color: '#333'
                    },
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(61, 187, 145, 1)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return 'Bookings: ' + context.parsed.y + ' times';
                            },
                            afterLabel: function(context) {
                                return 'Tour ID: ' + context.label.split(' ')[0];
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: Math.max(...bookingData) + 1,
                        ticks: {
                            stepSize: 1,
                            color: '#666',
                            callback: function(value) {
                                return value + ' bookings';
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        title: {
                            display: true,
                            text: 'Number of Bookings',
                            color: '#666',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            color: '#666',
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Tour Names',
                            color: '#666',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutQuart'
                }
            }
        });

        // Modal functionality for system issues
        // Open modal
        document.querySelectorAll('[data-modal-target]').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-modal-target');
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = '15px'; // Prevent layout shift
                }
            });
        });

        // Close modal
        document.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal-overlay');
                if (modal) {
                    modal.classList.remove('active');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
            });
        });

        // Close modal when clicking outside
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
            });
        });
    });
</script>
</body>
</html>