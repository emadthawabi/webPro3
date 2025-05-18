<?php
// Start the session
session_start();

// Set page title
$pageTitle = "My Travel Profile";

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "pathfinder");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get customer ID from session
$customerId = $_SESSION['customerid'];

// Handle image upload - keeping as requested
$message = '';
if(isset($_POST['upload'])) {
    // Check if file was uploaded without errors
    if(isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] == 0) {
        $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png"];
        $filename = $_FILES["profile_image"]["name"];
        $filetype = $_FILES["profile_image"]["type"];
        $filesize = $_FILES["profile_image"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) {
            $message = "Error: Please select a valid file format.";
        } else {
            // Verify file size - 5MB maximum
            $maxsize = 5 * 1024 * 1024;
            if($filesize > $maxsize) {
                $message = "Error: File size is larger than the allowed limit.";
            } else {
                // Verify MIME type of the file
                if(in_array($filetype, $allowed)) {
                    // Check if file exists before uploading it
                    if(file_exists("uploads/" . $filename)) {
                        $filename = uniqid() . '_' . $filename;
                    }

                    // Create uploads directory if it doesn't exist
                    if (!file_exists("uploads")) {
                        mkdir("uploads", 0777, true);
                    }

                    // Upload file
                    if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], "uploads/" . $filename)) {
                        // Update database with file path
                        $filepath = "uploads/" . $filename;
                        $sql = "UPDATE customer SET profilepic = ? WHERE customerid = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("si", $filepath, $customerId);

                        if($stmt->execute()) {
                            $message = "Profile picture updated successfully.";
                        } else {
                            $message = "Error: " . $stmt->error;
                        }
                    } else {
                        $message = "Error: There was an issue uploading your file. Please try again.";
                    }
                } else {
                    $message = "Error: There was a problem with your file. Please try again.";
                }
            }
        }
    } else {
        $message = "Error: " . $_FILES["profile_image"]["error"];
    }
}

// Get customer data
$sql = "SELECT * FROM customer WHERE customerid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();

// Get past tours data based on customer's ID instead of email
$pastTours = [];
$tourSql = "SELECT t.*, 
               d.country, d.city, d.continent, d.description,
               h.hotelname, h.stars, h.location,
               f.airport, f.type as flight_type, f.date as flight_date
        FROM tours t
        LEFT JOIN destination d ON t.destid = d.destid
        LEFT JOIN hotels h ON t.hotelid = h.hotelid
        LEFT JOIN flights f ON t.flightid = f.flightid
        LEFT JOIN customer c ON t.tourid = c.tourid
        WHERE c.customerid = ?";

$tourStmt = $conn->prepare($tourSql);
$tourStmt->bind_param("i", $customerId);
$tourStmt->execute();
$tourResult = $tourStmt->get_result();

while ($tour = $tourResult->fetch_assoc()) {
    $pastTours[] = $tour;
}

// Extract visited destinations and count continents
$destinationsVisited = [];
$continentCounts = [];
foreach ($pastTours as $tour) {
    if (!empty($tour['city']) && !empty($tour['country'])) {
        $destinationKey = $tour['city'] . ', ' . $tour['country'];
        if (!isset($destinationsVisited[$destinationKey])) {
            $destinationsVisited[$destinationKey] = [
                'city' => $tour['city'],
                'country' => $tour['country'],
                'continent' => $tour['continent'],
                'description' => $tour['description']
            ];

            // Count continents for the chart
            if (!empty($tour['continent'])) {
                if (!isset($continentCounts[$tour['continent']])) {
                    $continentCounts[$tour['continent']] = 1;
                } else {
                    $continentCounts[$tour['continent']]++;
                }
            }
        }
    }
}

// Format continent data for chart
$continentData = [];
foreach ($continentCounts as $continent => $count) {
    $continentData[] = [
        'name' => $continent,
        'count' => $count
    ];
}
$continentDataJson = json_encode($continentData);

// Map data for destinations
$mapData = [];
foreach ($destinationsVisited as $dest) {
    $mapData[] = [
        'city' => $dest['city'],
        'country' => $dest['country'],
        'continent' => $dest['continent']
    ];
}
$mapDataJson = json_encode($mapData);

// Calculate travel stats
$travelStats = [
    'countries' => count(array_unique(array_column($pastTours, 'country'))),
    'cities' => count(array_unique(array_column($pastTours, 'city'))),
    'tours' => count($pastTours),
    'hotels' => count(array_unique(array_column($pastTours, 'hotelid')))
];

// Default values if no user data
if (!$userData) {
    $userData = [
        'username' => 'traveler123',
        'email' => 'traveler@example.com',
        'gender' => 'male',
        'bdate' => '1990-01-01',
        'visanum' => 'A12345',
        'profilepic' => '',
        'ssn' => '',
        'customerid' => 1
    ];
}

// Calculate age from birthdate
$birthDate = new DateTime($userData['bdate']);
$today = new DateTime();
$age = $birthDate->diff($today)->y;

// Get user travel preferences
// For a real system, you might need to add a preferences table to your database
// For now, we'll generate them based on past tour data

$travelPreferences = [
    'continent' => '',
    'style' => '',
    'accommodation' => '',
    'budget' => ''
];

// Determine preferred continent from past tours
if (!empty($continentCounts)) {
    // Find the most visited continent
    arsort($continentCounts);
    $travelPreferences['continent'] = key($continentCounts);
}

// Determine travel style based on destinations
$adventureKeywords = ['mountains', 'hiking', 'adventure', 'wildlife', 'forest'];
$beachKeywords = ['beach', 'island', 'sea', 'ocean', 'coast'];
$cityKeywords = ['city', 'urban', 'metropolitan', 'shopping', 'cuisine'];
$culturalKeywords = ['museum', 'historical', 'ancient', 'heritage', 'temple'];

$styleScores = [
    'Adventure' => 0,
    'Beach' => 0,
    'City' => 0,
    'Cultural' => 0
];

foreach ($pastTours as $tour) {
    $description = strtolower($tour['description'] ?? '');

    foreach ($adventureKeywords as $keyword) {
        if (strpos($description, $keyword) !== false) {
            $styleScores['Adventure']++;
        }
    }

    foreach ($beachKeywords as $keyword) {
        if (strpos($description, $keyword) !== false) {
            $styleScores['Beach']++;
        }
    }

    foreach ($cityKeywords as $keyword) {
        if (strpos($description, $keyword) !== false) {
            $styleScores['City']++;
        }
    }

    foreach ($culturalKeywords as $keyword) {
        if (strpos($description, $keyword) !== false) {
            $styleScores['Cultural']++;
        }
    }
}

// Get the preferred style based on highest score
if (!empty($styleScores)) {
    arsort($styleScores);
    $travelPreferences['style'] = key($styleScores);
} else {
    $travelPreferences['style'] = 'Adventure'; // Default
}

// Determine accommodation preferences based on hotel stars
$hotelStars = array_column($pastTours, 'stars');
if (!empty($hotelStars)) {
    $avgStars = array_sum($hotelStars) / count($hotelStars);
    if ($avgStars >= 4.5) {
        $travelPreferences['accommodation'] = 'Luxury Hotels';
    } elseif ($avgStars >= 3.5) {
        $travelPreferences['accommodation'] = 'Premium Hotels';
    } elseif ($avgStars >= 2.5) {
        $travelPreferences['accommodation'] = 'Mid-range Hotels';
    } else {
        $travelPreferences['accommodation'] = 'Budget Hotels';
    }
} else {
    $travelPreferences['accommodation'] = 'Mid-range Hotels'; // Default
}

// Determine budget range based on tour prices
$tourPrices = array_column($pastTours, 'price');
if (!empty($tourPrices)) {
    $avgPrice = array_sum($tourPrices) / count($tourPrices);
    if ($avgPrice > 1000) {
        $travelPreferences['budget'] = '$1000-$5000';
    } elseif ($avgPrice > 500) {
        $travelPreferences['budget'] = '$500-$1000';
    } elseif ($avgPrice > 200) {
        $travelPreferences['budget'] = '$200-$500';
    } else {
        $travelPreferences['budget'] = 'Under $200';
    }
} else {
    $travelPreferences['budget'] = '$500-$1000'; // Default
}

// Get sample destinations for display
$destQuery = "SELECT * FROM destination ORDER BY RAND() LIMIT 5";
$destResult = $conn->query($destQuery);
$favDestinations = [];
while ($dest = $destResult->fetch_assoc()) {
    $favDestinations[] = $dest;
}



// Account info
$accountCreationDate = "2023-06-15";
$memberSince = round((time() - strtotime($accountCreationDate)) / (60 * 60 * 24 * 30));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo $pageTitle; ?></title>
    <style>
        /* Base Styles */
        :root {
            --primary: #3dbb91;
            --secondary: #2d9a77;
            --dark: #333;
            --light: #f9f9f9;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            line-height: 1.6;
        }

        .containerB {
            width: 100%;
            min-height: 100vh;
            background: radial-gradient(circle at 25% 25%, #a3b1c6 15%, transparent 15%),
            radial-gradient(circle at 75% 75%, #a3b1c6 15%, transparent 15%);
            background-size: 10px 10px;
            background-color: #e0e7ed;
            padding: 40px 20px;
        }

        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn.primary {
            background-color: var(--primary);
            color: white;
        }

        .btn.secondary {
            background-color: #f0f0f0;
            color: #333;
        }

        .btn.small-btn {
            padding: 5px 10px;
            font-size: 12px;
        }

        .profile-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Profile Header */
        .profile-header {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-cover {
            height: 150px;
            width: 100%;
            background-color: var(--primary);
            background-image: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 8px 8px 0 0;
            position: relative;
            overflow: hidden;
            margin-bottom: 60px;
        }

        .profile-avatar-container {
            position: absolute;
            top: 120px;
            margin-bottom: 15px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-initials {
            font-size: 40px;
            color: white;
            text-transform: uppercase;
        }

        .edit-avatar {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        .profile-info {
            text-align: center;
            margin-top: 10px;
        }

        .profile-info h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }

        .profile-role {
            color: #666;
            margin: 0 0 10px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 15px;
            background-color: #f0f0f0;
            font-size: 12px;
            margin-right: 5px;
        }

        .profile-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        /* Main Content Layout */
        .profile-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }

        .profile-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-main {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-header h3 {
            margin: 0;
            font-size: 18px;
            color: var(--dark);
        }

        .view-all {
            font-size: 14px;
            color: var(--primary);
            text-decoration: none;
        }

        /* Stats Card */
        .stats-card {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            text-align: center;
        }

        .stat-item {
            padding: 10px;
        }

        .stat-value {
            display: block;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-label {
            display: block;
            font-size: 14px;
            color: #666;
        }

        /* Tour History */
        .travel-timeline {
            position: relative;
            padding: 15px 0;
        }

        .timeline-line {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 15px;
            width: 3px;
            background: var(--primary);
            border-radius: 2px;
        }

        .timeline-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 5px 10px;
            border-radius: 15px;
            border: 1px solid #ddd;
            background-color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 13px;
        }

        .filter-btn:hover, .filter-btn.active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .timeline-item {
            position: relative;
            padding-left: 35px;
            margin-bottom: 20px;
        }

        .timeline-point {
            position: absolute;
            left: 7px;
            top: 12px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background-color: white;
            border: 3px solid var(--primary);
            z-index: 2;
        }

        .timeline-content {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .timeline-date {
            position: absolute;
            left: 35px;
            top: -8px;
            background-color: var(--primary);
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
        }

        .tour-title {
            margin: 5px 0;
            color: var(--dark);
            font-size: 16px;
        }

        .tour-details {
            display: grid;
            grid-template-columns: 1fr;
            grid-gap: 8px;
            margin: 10px 0;
        }

        .tour-detail {
            display: flex;
            align-items: center;
            font-size: 13px;
        }

        .tour-detail i {
            width: 20px;
            margin-right: 8px;
            color: var(--primary);
        }

        /* Destinations - Simplified */
        .destinations-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .destination-item {
            background-color: #f5f7fa;
            border-radius: 6px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }

        .destination-item:hover {
            background-color: #e8f1f8;
            transform: translateY(-2px);
        }

        .destination-icon {
            color: var(--primary);
            font-size: 16px;
        }

        .destination-info {
            line-height: 1.3;
        }

        .destination-location {
            font-weight: 600;
            font-size: 14px;
            margin: 0;
        }

        .destination-meta {
            font-size: 12px;
            color: #666;
            margin: 0;
        }

        .empty-destinations {
            text-align: center;
            padding: 20px;
            color: #999;
        }

        .empty-destinations i {
            font-size: 32px;
            color: #ddd;
            margin-bottom: 10px;
        }

        /* Account Card */
        .account-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .account-info p {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            margin: 0;
            font-size: 14px;
        }

        .account-info p:last-child {
            border-bottom: none;
        }

        .account-info p span:first-child {
            color: #666;
        }

        .account-info p span:last-child {
            font-weight: 600;
            color: var(--dark);
        }

        /* Traveler Info */
        .traveler-info p {
            display: flex;
            align-items: center;
            margin: 10px 0;
            font-size: 14px;
        }

        .traveler-info p i {
            width: 20px;
            margin-right: 10px;
            color: var(--primary);
        }

        /* Image Upload Form */
        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .upload-form {
            margin-top: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .image-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 15px 0;
            overflow: hidden;
            background-color: #f5f5f5;
            display: none;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Modal styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }

        .close-modal:hover {
            color: #333;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-content {
                grid-template-columns: 1fr;
            }

            .stats-card {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="containerB">
    <div class="profile-container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?php echo ($_SESSION['message_type'] == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                <?php
                echo $_SESSION['message'];
                // Clear the message after displaying
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-cover"></div>
            <div class="profile-avatar-container">
                <div class="profile-avatar">
                    <?php if (!empty($userData['profilepic'])): ?>
                        <img src="<?php echo $userData['profilepic']; ?>" alt="Profile Picture">
                    <?php else: ?>
                        <div class="avatar-initials"><?php echo substr($userData['username'], 0, 1); ?></div>
                    <?php endif; ?>
                </div>
                <button class="edit-avatar" id="openUploadForm"><i class="fas fa-camera"></i></button>
            </div>
            <div class="profile-info">
                <h1><?php echo $userData['username']; ?></h1>
                <p class="profile-role">
                    <?php echo $age; ?> year old <?php echo $userData['gender']; ?> traveler
                </p>
                <div class="travel-status">
                    <span class="status-badge">Visa #: <?php echo $userData['visanum']; ?></span>
                </div>
                <div class="profile-actions">
                    <button class="btn primary" id="editProfileBtn"><i class="fas fa-edit"></i> Edit Profile</button>
                    <a href="Tours.php" class="btn secondary"><i class="fas fa-compass"></i> Plan a Trip</a>
                </div>

            </div>
        </div>

        <!-- Main Content Area -->
        <div class="profile-content">
            <!-- Left Column -->
            <div class="profile-sidebar">
                <!-- Personal Information Card -->
                <div class="profile-card">
                    <h3>Personal Information</h3>
                    <div class="traveler-info">
                        <p><i class="fas fa-envelope"></i> <?php echo $userData['email']; ?></p>
                        <p><i class="fas fa-birthday-cake"></i> Born <?php echo date('F j, Y', strtotime($userData['bdate'])); ?></p>
                        <p><i class="fas fa-passport"></i> Visa #: <?php echo $userData['visanum']; ?></p>
                        <p><i class="fas fa-id-card"></i> SSN: <?php echo $userData['ssn']; ?></p>
                    </div>
                </div>

                <!-- Upload Form - Keeping as requested -->
                <div class="profile-card" id="uploadFormCard" style="display: none;">
                    <h3>Update Profile Picture</h3>
                    <?php if (!empty($message)): ?>
                        <div class="alert <?php echo strpos($message, 'Error') !== false ? 'alert-danger' : 'alert-success'; ?>">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="post" enctype="multipart/form-data" class="upload-form">
                        <div class="form-group">
                            <label for="profile_image">Select Image:</label>
                            <input type="file" name="profile_image" id="profile_image" class="form-control" accept=".jpg, .jpeg, .png, .gif" required>
                        </div>

                        <div class="image-preview" id="imagePreview">
                            <img src="#" alt="Image Preview">
                        </div>

                        <button type="submit" name="upload" class="btn primary">Upload Image</button>
                        <button type="button" class="btn secondary" id="cancelUpload">Cancel</button>
                    </form>
                </div>

                <!-- Travel Preferences -->

            </div>

            <!-- Right Column -->
            <div class="profile-main">
                <!-- Stats Card -->
                <div class="profile-card stats-card">
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $travelStats['countries']; ?></span>
                        <span class="stat-label">Countries</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $travelStats['cities']; ?></span>
                        <span class="stat-label">Cities</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $travelStats['tours']; ?></span>
                        <span class="stat-label">Tours</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $travelStats['hotels']; ?></span>
                        <span class="stat-label">Hotels</span>
                    </div>
                </div>

                <!-- Account Details Section - Moved up per instructions -->
                <div class="profile-card">
                    <div class="card-header">
                        <h3>Account Details</h3>
                    </div>

                    <div class="account-container">
                        <div class="account-info">
                            <p>
                                <span>Username</span>
                                <span><?php echo $userData['username']; ?></span>
                            </p>

                            <p>
                                <span>Email</span>
                                <span><?php echo $userData['email']; ?></span>
                            </p>
                            <p>
                                <span>Gender</span>
                                <span><?php echo ucfirst($userData['gender']); ?></span>
                            </p>
                            <p>
                                <span>Date of Birth</span>
                                <span><?php echo date('d/m/Y', strtotime($userData['bdate'])); ?></span>
                            </p>
                            <p>
                                <span>Visa Number</span>
                                <span><?php echo $userData['visanum']; ?></span>
                            </p>
                            <p>
                                <span>Account Status</span>
                                <span style="color: green; display: flex; align-items: center;">
                                    <i class="fas fa-circle" style="font-size: 10px; margin-right: 5px;"></i> Active
                                </span>
                            </p>


                        </div>
                    </div>
                </div>

                <!-- Travel History Section - Moved down per instructions -->
                <div class="profile-card">
                    <div class="card-header">
                        <h3>Travel History</h3>
                    </div>

                    <div class="timeline-filters">
                        <button class="filter-btn active" data-filter="all">All</button>
                        <button class="filter-btn" data-filter="europe">Europe</button>
                        <button class="filter-btn" data-filter="asia">Asia</button>
                        <button class="filter-btn" data-filter="africa">Africa</button>
                        <button class="filter-btn" data-filter="north america">North America</button>
                        <button class="filter-btn" data-filter="south america">South America</button>
                    </div>

                    <div class="travel-timeline">
                        <div class="timeline-line"></div>

                        <?php if (empty($pastTours)): ?>
                            <div style="text-align: center; padding: 20px;">
                                <i class="fas fa-route" style="font-size: 2rem; color: #ddd;"></i>
                                <p>No travel history found</p>


                                <a href="Tours.php" class="btn small-btn primary">Book Your First Tour</a>
                            </div>
                        <?php else: ?>
                            <?php foreach($pastTours as $tour): ?>
                                <div class="timeline-item" data-continent="<?php echo strtolower($tour['continent']); ?>">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-date">
                                        <?php echo date('M Y', strtotime($tour['flight_date'] ?? '2024-' . rand(1, 12) . '-' . rand(1, 28))); ?>
                                    </div>
                                    <div class="timeline-content">
                                        <h4 class="tour-title"><?php echo $tour['tourname']; ?></h4>

                                        <div class="tour-details">
                                            <div class="tour-detail">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span><?php echo $tour['city']; ?>, <?php echo $tour['country']; ?></span>
                                            </div>
                                            <div class="tour-detail">
                                                <i class="fas fa-globe"></i>
                                                <span><?php echo $tour['continent']; ?></span>
                                            </div>
                                            <div class="tour-detail">
                                                <i class="fas fa-calendar-day"></i>
                                                <span><?php echo $tour['duration']; ?> days</span>
                                            </div>
                                            <div class="tour-detail">
                                                <i class="fas fa-hotel"></i>
                                                <span><?php echo $tour['hotelname']; ?> (<?php echo $tour['stars']; ?>★)</span>
                                            </div>
                                            <div class="tour-detail">
                                                <i class="fas fa-plane"></i>
                                                <span><?php echo $tour['airport']; ?> (<?php echo $tour['flight_type']; ?>)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Destinations Visited - Simplified -->
                <div class="profile-card">
                    <div class="card-header">
                        <h3>Destinations Visited</h3>
                    </div>

                    <?php if (empty($destinationsVisited)): ?>
                        <div class="empty-destinations">
                            <i class="fas fa-map-marked-alt"></i>
                            <p>No destinations visited yet</p>
                            <a href="ThingsToDo.php" class="btn small-btn primary">Explore Destinations</a>
                        </div>
                    <?php else: ?>
                        <div class="destinations-container">
                            <?php
                            $continentIcons = [
                                'europe' => 'fa-landmark',
                                'asia' => 'fa-temple-buddhist',
                                'africa' => 'fa-tree',
                                'north america' => 'fa-mountain',
                                'south america' => 'fa-umbrella-beach',
                                'australia' => 'fa-water',
                                'antarctica' => 'fa-snowflake'
                            ];

                            foreach(array_values($destinationsVisited) as $dest):
                                $icon = $continentIcons[strtolower($dest['continent'])] ?? 'fa-map-marker-alt';
                                ?>
                                <div class="destination-item">
                                    <div class="destination-icon">
                                        <i class="fas <?php echo $icon; ?>"></i>
                                    </div>
                                    <div class="destination-info">
                                        <p class="destination-location"><?php echo $dest['city']; ?>, <?php echo $dest['country']; ?></p>
                                        <p class="destination-meta"><?php echo ucfirst($dest['continent']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Edit Profile</h2>
        <form id="editProfileForm" method="post" action="update_profile.php">
            <input type="hidden" name="action" value="update_profile">
            <input type="hidden" name="customerid" value="<?php echo $userData['customerid']; ?>">

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo $userData['username']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo $userData['email']; ?>" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="male" <?php echo ($userData['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($userData['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label for="bdate">Date of Birth:</label>
                <input type="date" id="bdate" name="bdate" class="form-control" value="<?php echo $userData['bdate']; ?>" required>
            </div>

            <div class="form-group">
                <label for="visanum">Visa Number:</label>
                <input type="text" id="visanum" name="visanum" class="form-control" value="<?php echo $userData['visanum']; ?>" required>
            </div>

            <div class="form-group">
                <label for="ssn">SSN:</label>
                <input type="text" id="ssn" name="ssn" class="form-control" value="<?php echo $userData['ssn']; ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
            </div>

            <button type="submit" class="btn primary">Save Changes</button>
            <button type="button" class="btn secondary close-modal-btn">Cancel</button>
        </form>
    </div>
</div>

<script>
    // Image preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('profile_image');
        const imagePreview = document.getElementById('imagePreview');
        const uploadFormCard = document.getElementById('uploadFormCard');
        const openUploadFormBtn = document.getElementById('openUploadForm');
        const cancelUploadBtn = document.getElementById('cancelUpload');

        // Show/hide upload form
        openUploadFormBtn.addEventListener('click', function() {
            uploadFormCard.style.display = 'block';
            uploadFormCard.scrollIntoView({ behavior: 'smooth' });
        });

        cancelUploadBtn.addEventListener('click', function() {
            uploadFormCard.style.display = 'none';
        });

        // Preview image before upload
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();

                reader.addEventListener('load', function() {
                    imagePreview.querySelector('img').src = reader.result;
                    imagePreview.style.display = 'block';
                });

                reader.readAsDataURL(file);
            }
        });

        // Timeline filters
        const filterButtons = document.querySelectorAll('.filter-btn');
        const timelineItems = document.querySelectorAll('.timeline-item');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));

                // Add active class to clicked button
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');

                timelineItems.forEach(item => {
                    if (filter === 'all') {
                        item.style.display = 'block';
                    } else {
                        if (item.getAttribute('data-continent') === filter) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });
            });
        });

        // Edit Profile Modal
        const modal = document.getElementById('editProfileModal');
        const editProfileBtn = document.getElementById('editProfileBtn');
        const closeModalBtns = document.querySelectorAll('.close-modal, .close-modal-btn');

        editProfileBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
        });

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        });

        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        <?php if (!empty($message)): ?>
        // Show upload form if there was a message (error or success)
        uploadFormCard.style.display = 'block';
        <?php endif; ?>
    });
</script>
<?php $conn->close(); ?>
</body>
</html>