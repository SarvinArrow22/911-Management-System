<?php
// Assuming you have an active connection to your database
include('includes/db.php'); // Replace with your actual DB connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the values from the submitted form
    $id = $_POST['id']; // The ID of the call log to update
    $serviceType = $_POST['service_type'];
    $callType = $_POST['call_type'];
    $callDate = $_POST['call_date'];
    $callTime = $_POST['call_time'];
    $contactNumber = $_POST['contact_number'];
    $count = $_POST['count'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $location = $_POST['location'];

    // Prepare the SQL Query using prepared statements to prevent SQL injection
    $query = "UPDATE call_logs cl
              LEFT JOIN service_types st ON cl.type_of_service = st.id
              LEFT JOIN call_types ct ON cl.call_type = ct.id
              SET 
                  cl.type_of_service = ?,
                  cl.call_type = ?,
                  cl.call_date = ?,
                  cl.call_time = ?,
                  cl.contact_number = ?,
                  cl.call_count = ?,
                  cl.name = ?,
                  cl.age = ?,
                  cl.location = ?
              WHERE cl.id = ?";

    // Prepare the statement
    if ($stmt = mysqli_prepare($conn, $query)) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "sssssssssi", 
            $serviceType, $callType, $callDate, $callTime, 
            $contactNumber, $count, $name, $age, $location, $id);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the main page with a success message
            header('Location: user_dashboard.php?success=1');
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing the query: " . mysqli_error($conn);
    }
}
?>
