<?php
// Start the session
session_start();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    $errors = [];

    // Required fields
    $required_fields = ['airport', 'time', 'begin', 'destid', 'price', 'type', 'date'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "The field '$field' is required.";
        }
    }

    // Price validation - can contain $ symbol
    if (!empty($_POST['price']) && !preg_match('/^\$?[0-9]+(\.\d{1,2})?$/', $_POST['price'])) {
        $errors[] = "Price must be a valid number.";
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
            $query = "INSERT INTO flights (airport, time, begin, destid, price, type, date) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $db->prepare($query);

            if (!$stmt) {
                throw new Exception("Prepare failed: " . $db->error);
            }

            // Bind parameters
            $stmt->bind_param(
                "sssssss",
                $_POST['airport'],
                $_POST['time'],
                $_POST['begin'],
                $_POST['destid'],
                $_POST['price'],
                $_POST['type'],
                $_POST['date']
            );

            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            // Close the statement and connection
            $stmt->close();
            $db->close();

            // Set success message and redirect
            $_SESSION['flight_add_success'] = "Flight was added successfully!";

            // Redirect back to the referring page or to a default page
            $redirect_to = isset($_POST['referer_page']) ? $_POST['referer_page'] : 'admin.php';
            header("Location: ../Admin/" . $redirect_to);
            exit();

        } catch (Exception $e) {
            // Set error message
            $_SESSION['flight_add_error'] = "Failed to add flight: " . $e->getMessage();

            // Store form data in session for repopulating the form
            $_SESSION['form_data'] = $_POST;

            // Redirect back to the referring page or to a default page
            $redirect_to = isset($_POST['referer_page']) ? $_POST['referer_page'] : 'admin.php';
            header("Location: ../Admin/" . $redirect_to);
            exit();
        }
    } else {
        // If there are validation errors, set error message with all errors
        $_SESSION['flight_add_error'] = "Please correct the following errors: " . implode(" ", $errors);

        // Store form data in session for repopulating the form
        $_SESSION['form_data'] = $_POST;

        // Redirect back to the referring page or to a default page
        $redirect_to = isset($_POST['referer_page']) ? $_POST['referer_page'] : 'admin.php';
        header("Location: ../Admin/" . $redirect_to);
        exit();
    }
} else {
    // If someone tries to access this file directly without POST data
    $_SESSION['flight_add_error'] = "Invalid request method.";
    header("Location: ../Admin/admin.php");
    exit();
}
?>