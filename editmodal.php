<?php
session_start();  // Make sure the session is started
include('includes/db.php'); // Include database connection

// Fetch the logged-in user's information
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT team FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $team = $user['team'];

        // Determine which table to query based on the user's team
        switch ($team) {
            case 'alpha':
                $table_name = 'alpha_tbl';
                break;
            case 'bravo':
                $table_name = 'bravo_tbl';
                break;
            case 'charlie':
                $table_name = 'charlie_tbl';
                break;
            default:
                echo "Unknown team. Please contact the administrator.";
                exit();
        }
    } else {
        echo "User data not found.";
        exit();
    }
} else {
    echo "User not logged in.";
    exit();
}

// Check if the update form is submitted
if (isset($_POST['update'])) {
    // Get the data from the form
    $id = $_POST['id'];
    $agent_id = $_POST['agent_id'];
    $team = $_POST['team'];  // This is not used in update, but you might want to keep it
    $service_type = $_POST['service_type'];
    $call_type = $_POST['call_type'];
    $call_date = $_POST['call_date'];
    $call_time = $_POST['call_time'];
    $contact_number = $_POST['contact_number'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $location = $_POST['location'];
    $reason_of_call = $_POST['reason_of_call'];
    $actions_taken = $_POST['action_taken'];
    $remarks = $_POST['remarks'];
    $status = $_POST['status'];

    // Perform validation and sanitization of input fields
    if (empty($agent_id) || empty($service_type) || empty($call_type) || empty($call_date) || empty($call_time) || empty($contact_number) || empty($name) || empty($age) || empty($status)) {
        echo "<script>alert('Please fill in all required fields.');</script>";
        exit();
    }

    // Sanitize the input data to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);
    $agent_id = mysqli_real_escape_string($conn, $agent_id);
    $team = mysqli_real_escape_string($conn, $team);
    $service_type = mysqli_real_escape_string($conn, $service_type);
    $call_type = mysqli_real_escape_string($conn, $call_type);
    $call_date = mysqli_real_escape_string($conn, $call_date);
    $call_time = mysqli_real_escape_string($conn, $call_time);
    $contact_number = mysqli_real_escape_string($conn, $contact_number);
    $name = mysqli_real_escape_string($conn, $name);
    $age = mysqli_real_escape_string($conn, $age);
    $location = mysqli_real_escape_string($conn, $location);
    $reason_of_call = mysqli_real_escape_string($conn, $reason_of_call);
    $actions_taken = mysqli_real_escape_string($conn, $actions_taken);
    $remarks = mysqli_real_escape_string($conn, $remarks);
    $status = mysqli_real_escape_string($conn, $status);

    // Update the record in the correct table based on user's team
    $update_query = "
        UPDATE $table_name 
        SET 
            agent_id = '$agent_id',
            team = '$team',
            type_of_service = '$service_type',
            call_type = '$call_type',
            call_date = '$call_date',
            call_time = '$call_time',
            contact_number = '$contact_number',
            name = '$name',
            age = '$age',
            location = '$location',
            reason_of_call = '$reason_of_call',
            actions_taken = '$actions_taken',
            remarks = '$remarks',
            status = '$status'
        WHERE id = '$id'
    ";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Record updated successfully!'); window.location.href = 'newsoks.php';</script>";
    } else {
        echo "<script>alert('Error updating record.');</script>";
    }
}
?>
 