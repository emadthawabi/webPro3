<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_profile') {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "pathfinder");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $customerId = $_POST['customerid'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $bdate = $_POST['bdate'];
    $visanum = $_POST['visanum'];
    $ssn = $_POST['ssn'];

    // Check if email is already in use by another user
    $checkEmailSql = "SELECT customerid FROM customer WHERE email = ? AND customerid != ?";
    $checkStmt = $conn->prepare($checkEmailSql);
    $checkStmt->bind_param("si", $email, $customerId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Email is already in use by another user
        $_SESSION['message'] = "Email address is already in use by another account. Please use a different email.";
        $_SESSION['message_type'] = "error";
        $checkStmt->close();
        $conn->close();
        header("Location: profile.php");
        exit();
    }
    $checkStmt->close();

    // Prepare SQL statement for update
    $sql = "UPDATE customer SET 
            username = ?,
            email = ?,
            gender = ?,
            bdate = ?,
            visanum = ?,
            ssn = ?";

    // Add password update if provided
    $params = [$username, $email, $gender, $bdate, $visanum, $ssn];
    $types = "ssssss";

    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        $sql .= ", password = ?";
        $params[] = $password;
        $types .= "s";
    }

    $sql .= " WHERE customerid = ?";
    $params[] = $customerId;
    $types .= "i";

    // Execute update
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        // Update the session variables if they changed
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        // Set success message
        $_SESSION['message'] = "Profile updated successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        // Set error message
        $_SESSION['message'] = "Error updating profile: " . $stmt->error;
        $_SESSION['message_type'] = "error";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to profile page
    header("Location: profile.php");
    exit();
}
?>