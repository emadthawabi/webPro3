<?php

$ssn = 0;
$username = 0;
$email = 0;
$gender = 0;
$bdate = 0;
$visanum = 0;
$password = 0;
$confirmPassword = 0;
$error_message = "";
$registration_success = false;

// Get the referer page to redirect back to
$referer_page = isset($_POST['referer_page']) ? $_POST['referer_page'] : 'index.php';

//and (isset($_POST["genderMale"]) or isset($_POST["genderMale"]) )

if(isset($_POST["signupssn"]) and isset($_POST["signupName"]) and isset($_POST["signupEmail"])
    and isset($_POST["signupBdate"]) and isset($_POST["signupVisa"])
    and isset($_POST["signupPassword"]) and isset($_POST["signupConfirmPassword"])

    and !empty($_POST["signupssn"]) and !empty($_POST["signupName"]) and !empty($_POST["signupEmail"])
    and !empty($_POST["signupBdate"]) and !empty($_POST["signupVisa"])
    and !empty($_POST["signupPassword"]) and !empty($_POST["signupConfirmPassword"])
){

    $ssn = $_POST["signupssn"];
    $username = $_POST["signupName"];
    $email = $_POST["signupEmail"];
    //and !empty($_POST["signupGender"])
    $gender = $_POST["gender"];
    //and isset($_POST["genderMale"])
    $bdate = $_POST["signupBdate"];
    $visanum = $_POST["signupVisa"];
    $password = $_POST["signupPassword"];
    $confirmPassword = $_POST["signupConfirmPassword"];

    // Check if password matches confirm password
    if ($password !== $confirmPassword) {
        $error_message = "Passwords do not match. Please try again.";
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['signup_error'] = $error_message;
        $_SESSION['active_tab'] = 'signup';
        $_SESSION['show_auth_modal'] = true;
        header("Location: $referer_page");
        exit();
    } else {
        try {
            $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

            // Check if user already exists with the same email
            $check_query = "SELECT * FROM `customer` WHERE `email` = '$email'";
            $result = $db->query($check_query);

            if ($result->num_rows > 0) {
                $error_message = "A user with this email already exists.";
                // Start session if not already started
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['signup_error'] = $error_message;
                $_SESSION['active_tab'] = 'signup';
                $_SESSION['show_auth_modal'] = true;
                $_SESSION['form_data'] = $_POST; // Save form data to repopulate fields
                header("Location: $referer_page");
                exit();
            } else {
                // Insert new user
                $qs = "INSERT INTO `customer` (`customerid`, `ssn`, `username`, `password`, `email`, `gender`, `bdate`, `tourid`, `hotelid`, `flightid`, `destid`, `visanum`) VALUES ('', '$ssn', '$username', '$password', '$email', '$gender', '$bdate', NULL, NULL, NULL, NULL, '$visanum')";
                $db->query($qs);

                // Get the newly created customer ID
                $new_customer_id = $db->insert_id;

                $db->commit();
                $registration_success = true;

                // Start session if not already started
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // Automatically log in the new user
                $_SESSION['loggedin'] = true;
                $_SESSION['customerid'] = $new_customer_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;

                // Check if this is an admin user (same logic as login.php)
                if ($email == "emad@gmail.com" && $password == "1234" ||
                    $email == "yousef@gmail.com" && $password == "1212") {
                    $_SESSION['is_admin'] = true;
                } else {
                    $_SESSION['is_admin'] = false;
                }

                // Set success message (optional - you might want to remove this since they're now logged in)
                $_SESSION['signup_success'] = "Registration successful! You are now logged in.";

                // Redirect to the page they were on (now logged in)
                header("Location: $referer_page");
                exit();
            }

            $db->close();
        } catch(Exception $e) {
            $error_message = "Database error: " . $e->getMessage();
            // Start session if not already started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['signup_error'] = $error_message;
            $_SESSION['active_tab'] = 'signup';
            $_SESSION['show_auth_modal'] = true;
            $_SESSION['form_data'] = $_POST; // Save form data to repopulate fields
            header("Location: $referer_page");
            exit();
        }
    }
}

// If we get here without a successful registration or redirection, it means something went wrong
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['signup_error'] = "Something went wrong with the registration. Please try again.";
$_SESSION['active_tab'] = 'signup';
$_SESSION['show_auth_modal'] = true;
$_SESSION['form_data'] = $_POST; // Save form data to repopulate fields
header("Location: $referer_page");
exit();
?>