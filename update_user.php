<?php
// Include database connection (replace with your actual connection details)
include('includes/db.php');

// Check if data was sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data sent via AJAX
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate the incoming data
    if (isset($data['id'], $data['first_name'], $data['last_name'], $data['mobile_number'], $data['email'], $data['team'], $data['username'], $data['role'])) {
        
        // Get user data from AJAX request
        $userId = mysqli_real_escape_string($conn, $data['id']);
        $firstName = mysqli_real_escape_string($conn, $data['first_name']);
        $lastName = mysqli_real_escape_string($conn, $data['last_name']);
        $mobileNumber = mysqli_real_escape_string($conn, $data['mobile_number']);
        $email = mysqli_real_escape_string($conn, $data['email']);
        $team = mysqli_real_escape_string($conn, $data['team']);
        $username = mysqli_real_escape_string($conn, $data['username']);
        $role = mysqli_real_escape_string($conn, $data['role']);

        // Prepare the SQL query to update user data
        $query = "UPDATE users SET 
                    first_name = '$firstName', 
                    last_name = '$lastName', 
                    mobile_number = '$mobileNumber', 
                    email = '$email', 
                    team = '$team', 
                    username = '$username', 
                    role = '$role'
                  WHERE id = '$userId'";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            // Success response
            echo json_encode(['success' => true]);
        } else {
            // Failure response
            echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
        }
    } else {
        // Invalid data sent
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
