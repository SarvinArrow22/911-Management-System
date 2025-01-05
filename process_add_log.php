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
    $user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($conn, $_POST['user_id']) : '';
    $team = isset($_POST['team']) ? mysqli_real_escape_string($conn, $_POST['team']) : '';
    $call_time = isset($_POST['call_time']) ? mysqli_real_escape_string($conn, $_POST['call_time']) : '';
    $status = isset($_POST['status']) ? mysqli_real_escape_string($conn, $_POST['status']) : '';

    // Automatically set the current date for call_date
    $call_date = date("Y-m-d"); // Current date in 'YYYY-MM-DD' format

    // Validate phone number format (more flexible pattern)
    if (!preg_match("/^\+?[0-9]{1,4}[\s\-]?[0-9]{1,4}[\s\-]?[0-9]{1,4}$/", $contactNumber)) {
        // If phone number is not valid
        header('Location: user_dashboard2.php?error=Invalid phone number format');
        exit;
    }

    // Validate the status value
    if ($status !== 'pending_case' && $status !== 'closed_case') {
        header('Location: user_dashboard2.php?error=Invalid status value');
        exit;
    }

    // Dynamically set the table based on the $team value
    $tableName = '';
    if ($team === 'alpha') {
        $tableName = 'alpha_tbl';
    } elseif ($team === 'bravo') {
        $tableName = 'bravo_tbl';
    } elseif ($team === 'charlie') {
        $tableName = 'charlie_tbl';
    } else {
        $tableName = 'call_logs';  // Default table for unassigned or invalid team
    }

    // Prepare the SQL query to insert the new log into the dynamically selected table
    $query = "INSERT INTO $tableName (type_of_service, call_type, contact_number, name, age, location, reason_of_call, actions_taken, remarks, call_time, call_date, agent_id, team, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind parameters to the query, including the new call_date parameter
    $stmt->bind_param("ssssssssssssss", $serviceType, $callType, $contactNumber, $name, $age, $location, $reasonOfCall, $actionTaken, $remarks, $call_time, $call_date, $user_id, $team, $status);

    // Execute the query
    if ($stmt->execute()) {
        header('Location: newsoks.php');
        exit;
    } else {
        header('Location: add_log_form.php?error=' . $stmt->error);
        exit;
    }
} else {
    echo "Invalid request.";
}
?>
