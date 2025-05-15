<?php
try {
    // Database connection
    $db = new mysqli("localhost", "root", "", "pathfinder", 3306);
    if ($db->connect_error) {
        throw new Exception("Connection failed: " . $db->connect_error);
    }

    // Prepare the insert statement
    $query = "INSERT INTO tours (tourname, destid, flightid, hotelid, price, rating, duration) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $db->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("siiiddi", $_POST['tourname'], $destid, $flightid, $hotelid, $price, $rating, $duration);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    // Success message
    $_SESSION['tour_add_success'] = "Tour was added successfully!";
    header("Location: ../Admin/admin.php");
    exit();

} catch (Exception $e) {
    error_log("Error in addTour.php: " . $e->getMessage());
    $_SESSION['tour_add_error'] = "Failed to add tour: " . $e->getMessage();
    header("Location: ../Admin/admin.php");
    exit();
}

// Start the session
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store original submitted data in case of errors
    $_SESSION['form_data'] = $_POST;

    // Validate input data
    $errors = [];
    $required_fields = ['tourname', 'destid', 'flightid', 'hotelid', 'price', 'rating', 'duration'];

    // Check required fields
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "The field '$field' is required.";
        }
    }

    // Validate numeric fields
    if (!empty($_POST['price']) && !is_numeric(str_replace('$', '', $_POST['price']))) {
        $errors[] = "Price must be a valid number.";
    }

    if (!empty($_POST['rating']) && (!is_numeric($_POST['rating']) || $_POST['rating'] < 0 || $_POST['rating'] > 5)) {
        $errors[] = "Rating must be a number between 0 and 5.";
    }

    if (!empty($_POST['duration']) && (!is_numeric($_POST['duration']) || $_POST['duration'] < 1)) {
        $errors[] = "Duration must be a positive number.";
    }

    // If no validation errors, proceed with database insertion
    if (empty($errors)) {
        try {
            // Connect to database
            $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

            // Check connection
            if ($db->connect_error) {
                throw new Exception("Connection failed: " . $db->connect_error);
            }

            // Prepare the insert statement
            $query = "INSERT INTO tours (tourname, destid, flightid, hotelid, price, rating, duration) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($query);

            if (!$stmt) {
                throw new Exception("Prepare failed: " . $db->error);
            }

            // Format price
            $price = floatval(str_replace('$', '', $_POST['price']));
            $rating = floatval($_POST['rating']);
            $duration = intval($_POST['duration']);
            $destid = intval($_POST['destid']);
            $flightid = intval($_POST['flightid']);
            $hotelid = intval($_POST['hotelid']);

            // Log the values being inserted
            error_log("Inserting tour: tourname={$_POST['tourname']}, destid={$destid}, flightid={$flightid}, hotelid={$hotelid}, price={$price}, rating={$rating}, duration={$duration}");

            // Bind parameters
            $stmt->bind_param("siiiddi", $_POST['tourname'], $destid, $flightid, $hotelid, $price, $rating, $duration);

            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            // Clear form data
            unset($_SESSION['form_data']);
            $_SESSION['tour_add_success'] = "Tour was added successfully!";
            header("Location: ../Admin/admin.php");
            exit();

        } catch (Exception $e) {
            // Log the error
            error_log("Error in addTour.php: " . $e->getMessage());
            $_SESSION['tour_add_error'] = "Failed to add tour: " . $e->getMessage();
            header("Location: ../Admin/admin.php");
            exit();
        } finally {
            // Close the statement and connection
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($db)) {
                $db->close();
            }
        }
    } else {
        // If there are validation errors, set error message
        $_SESSION['tour_add_error'] = "Please correct the following errors: " . implode(" ", $errors);
        header("Location: ../Admin/admin.php");
        exit();
    }
} else {
    // If someone tries to access this file directly without POST data
    $_SESSION['tour_add_error'] = "Invalid request method.";
    header("Location: ../Admin/admin.php");
    exit();
}
?>
