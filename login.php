<?php
// Start the session
session_start();

// Database connection details
$servername = "localhost";
$username = "root"; // Typically "root" for local development
$password = ""; // Typically empty for local development
$dbname = "pathfinder"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for the referring page
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
// Extract just the page name from the referer URL
$referer_parts = parse_url($referer);
$referer_path = isset($referer_parts['path']) ? $referer_parts['path'] : '/index.php';
$referer_page = basename($referer_path);

// Store referer in session if not already set from form
if (!isset($_POST['referer_page'])) {
    $_SESSION['referer_page'] = $referer_page;
} else {
    // Use the one passed in the form
    $_SESSION['referer_page'] = $_POST['referer_page'];
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from form
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT customerid, username, email, password FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify password (assuming plain text for now, should use password_hash/verify in production)
        if ($password == $row['password']) {
            // Password is correct, set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['customerid'] = $row['customerid'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            // Check if this is an admin user
            if ($email == "emad@gmail.com" && $password == "1234" ||
                $email == "yousef@gmail.com" && $password == "1212") {
                $_SESSION['is_admin'] = true;
            } else {
                $_SESSION['is_admin'] = false;
            }

            // Redirect to the page they were on
            $redirect_page = isset($_SESSION['referer_page']) ? $_SESSION['referer_page'] : 'index.php';
            header("Location: " . $redirect_page);
            exit();
        } else {
            // Invalid password
            $_SESSION['login_error'] = "Invalid email or password!!";
            $_SESSION['show_auth_modal'] = true; // Show modal with error message
            $_SESSION['active_tab'] = 'login'; // Make sure login tab is active

            // Redirect back to the page they were on
            $redirect_page = isset($_SESSION['referer_page']) ? $_SESSION['referer_page'] : 'index.php';
            header("Location: " . $redirect_page);
            exit();
        }
    } else {
        // Email not found
        $_SESSION['login_error'] = "Invalid email or password!!";
        $_SESSION['show_auth_modal'] = true; // Show modal with error message
        $_SESSION['active_tab'] = 'login'; // Make sure login tab is active

        // Redirect back to the page they were on
        $redirect_page = isset($_SESSION['referer_page']) ? $_SESSION['referer_page'] : 'index.php';
        header("Location: " . $redirect_page);
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // If not a POST request, redirect to index
    header("Location: index.php");
    exit();
}
?>