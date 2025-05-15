<?php
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
    $required_fields = ['continent', 'country', 'city', 'description'];

    // Check required fields
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "The field '$field' is required.";
        }
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
            $query = "INSERT INTO destination (continent, country, city, description) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($query);

            if (!$stmt) {
                throw new Exception("Prepare failed: " . $db->error);
            }

            // Bind parameters
            $stmt->bind_param("ssss", $_POST['continent'], $_POST['country'], $_POST['city'], $_POST['description']);

            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            // Clear form data
            unset($_SESSION['form_data']);
            $_SESSION['destination_add_success'] = "Destination was added successfully!";
            header("Location: ../Admin/admin.php");
            exit();

        } catch (Exception $e) {
            // Log the error
            error_log("Error in addDestination.php: " . $e->getMessage());
            $_SESSION['destination_add_error'] = "Failed to add destination: " . $e->getMessage();
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
        $_SESSION['destination_add_error'] = "Please correct the following errors: " . implode(" ", $errors);
        header("Location: ../Admin/admin.php");
        exit();
    }
} else {
    // If someone tries to access this file directly without POST data
    $_SESSION['destination_add_error'] = "Invalid request method.";
    header("Location: ../Admin/admin.php");
    exit();
}
?>
