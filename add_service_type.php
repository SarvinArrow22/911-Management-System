<?php
// Include the database connection
include('includes/db.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $call_type_id = $_POST['call_type_id']; // The selected call type ID
    $service_type = $_POST['service_type']; // The new service type

    // Insert the new service type into the service_types table
    $query = "INSERT INTO service_types (service_type) VALUES ('$service_type')";
    
    // Execute the query and check if it was successful
    if (mysqli_query($conn, $query)) {
        // Get the last inserted service_type ID
        $service_type_id = mysqli_insert_id($conn);

        // Insert the call_type_id into call_types table
        $query2 = "UPDATE call_types SET service_type_id = '$service_type_id' WHERE id = '$call_type_id'";

        // Execute the second query
        if (mysqli_query($conn, $query2)) {
            echo "Service Type added successfully!";
        } else {
            echo "Error updating call type: " . mysqli_error($conn);
        }
    } else {
        // Error message if something goes wrong
        echo "Error: " . mysqli_error($conn);
    }

    // Redirect back to the settings page after adding the service type
    header("Location: admin-settings.php"); // Change this to your target page
    exit();
}
?>
