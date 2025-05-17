<?php
// Check if customerid is set
if (isset($_POST['customerid']) && !empty($_POST['customerid'])) {
    $customerId = $_POST['customerid'];

    try {
        // Connect to database
        $db = new mysqli("localhost", "root", "", "pathfinder", 3306);

        // Prepare select statement
        $stmt = $db->prepare("SELECT * FROM customer WHERE customerid = ?");
        $stmt->bind_param("i", $customerId);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Get the customer data
            $customer = $result->fetch_assoc();

            // Return as JSON
            header('Content-Type: application/json');
            echo json_encode($customer);
        } else {
            http_response_code(404);
            echo "Customer not found";
        }

        // Close statement and connection
        $stmt->close();
        $db->close();

    } catch (Exception $e) {
        http_response_code(500);
        echo "Database error: " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "Customer ID is required";
}
?>