<?php
include('includes/db.php'); // Include the database connection

// Check if callTypeId is provided in the AJAX request
if (isset($_GET['callTypeId'])) {
    $callTypeId = mysqli_real_escape_string($conn, $_GET['callTypeId']);

    // Fetch the call types related to the selected service type (using call_type_id)
    $query = "SELECT * FROM call_types WHERE id = '$callTypeId'";
    $result = mysqli_query($conn, $query);

    // Check if any call types were found
    if (mysqli_num_rows($result) > 0) {
        // Output the options for the Call Type dropdown
        echo "<option value=''>Select Call Type</option>"; // Default option
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['id']}'>{$row['call_type']}</option>";
        }
    } else {
        // If no call types found, provide a default message
        echo "<option value=''>No Call Types available</option>";
    }
} else {
    // If no callTypeId is passed, return the default option
    echo "<option value=''>Select Call Type</option>";
}
?>
