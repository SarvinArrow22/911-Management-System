<?php
// Include database connection
include('includes/db.php');

// Check if the 'id' parameter is passed in the URL (for identifying the record to be deleted)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Sanitize the ID to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);

    // Delete query to remove the record from the call_types table
    $delete_query = "DELETE FROM call_types WHERE id = '$id'";

    // Execute the query and check for success
    if (mysqli_query($conn, $delete_query)) {
        // Redirect to the page with a success message
        header("Location: admin-settings.php?success=Call Type deleted successfully!");
        exit();
    } else {
        // Error occurred during deletion
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // ID not provided, redirect or show an error message
    echo "Call Type ID is required!";
}
?>
