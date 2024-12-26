<?php
// Include the database connection
include('includes/db.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form input
    $call_type = mysqli_real_escape_string($conn, $_POST['call_type']);
    $service_type_id = mysqli_real_escape_string($conn, $_POST['service_type_id']); // Get the service_type_id from the form

    // Validate input (you can add more validation as needed)
    if (empty($call_type) || empty($service_type_id)) {
        echo "Call Type and Service Type are required!";
    } else {
        // Insert into the database
        $query = "INSERT INTO call_types (call_type, service_type_id) VALUES ('$call_type', '$service_type_id')";
        
        if (mysqli_query($conn, $query)) {
            // Success: redirect or show a success message
            header("Location: admin-settings.php?success=Call Type added successfully!");
            exit();
        } else {
            // Error: show an error message
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
