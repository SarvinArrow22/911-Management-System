<?php
include('includes/db.php'); // Include the database connection

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get form inputs
    $serviceType = isset($_POST['service_type']) ? mysqli_real_escape_string($conn, $_POST['service_type']) : '';
    $callType = isset($_POST['call_type']) ? mysqli_real_escape_string($conn, $_POST['call_type']) : '';
    $contactNumber = isset($_POST['contactNumber']) ? mysqli_real_escape_string($conn, $_POST['contactNumber']) : '';
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $age = isset($_POST['age']) ? mysqli_real_escape_string($conn, $_POST['age']) : '';
    $location = isset($_POST['location']) ? mysqli_real_escape_string($conn, $_POST['location']) : '';
    $reasonOfCall = isset($_POST['reason_of_call']) ? mysqli_real_escape_string($conn, $_POST['reason_of_call']) : '';
    $actionTaken = isset($_POST['action_taken']) ? mysqli_real_escape_string($conn, $_POST['action_taken']) : '';
    $remarks = isset($_POST['remarks']) ? mysqli_real_escape_string($conn, $_POST['remarks']) : '';
    $user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($conn, $_POST['user_id']) : ''; // Fetch the user_id from the form
    $call_time = isset($_POST['call_time']) ? mysqli_real_escape_string($conn, $_POST['call_time']) : ''; // New call time field
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : ''; // Fetch the status from the form

    // Validate inputs
    // if (empty($serviceType) || empty($callType) || empty($contactNumber) || empty($name) || empty($age) || empty($location) || empty($call_time) || empty($status)) {
    //     // Redirect back with an error message if any required field is missing
    //     header('Location: add_log_form.php?error=All fields are required');
    //     exit;
    // }

    // Validate phone number format (more flexible pattern)
    if (!preg_match("/^\+?[0-9]{1,4}[\s\-]?[0-9]{1,4}[\s\-]?[0-9]{1,4}$/", $contactNumber)) {
        // If phone number is not valid
        header('Location: add_log_form.php?error=Invalid phone number format');
        exit;
    }

    // Validate the status value (optional but a good practice for security)
    if ($status !== 'pending_case' && $status !== 'closed_case') {
        header('Location: add_log_form.php?error=Invalid status value');
        exit;
    }

    // Prepare the SQL query to insert the new log into the database
    $stmt = $conn->prepare("INSERT INTO call_logs (type_of_service, call_type, contact_number, name, age, location, reason_of_call, actions_taken, remarks, call_time, agent_id, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters to the query, including the new status parameter
    $stmt->bind_param("ssssssssssss", $serviceType, $callType, $contactNumber, $name, $age, $location, $reasonOfCall, $actionTaken, $remarks, $call_time, $user_id, $status);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the dashboard or another page upon success
        header('Location: user_dashboard.php'); // Adjust with the correct path
        exit;
    } else {
        // Error handling if the query fails
        header('Location: add_log_form.php?error=' . $stmt->error);
        exit;
    }
} else {
    // If the form is not submitted via POST
    echo "Invalid request.";
}
?>
