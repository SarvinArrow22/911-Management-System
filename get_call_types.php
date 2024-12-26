<?php
// Include the database connection
include('./includes/db.php');

// Check if service_type_id is passed
if (isset($_GET['service_type_id'])) {
    $service_type_id = (int)$_GET['service_type_id'];
    
    // Fetch the call types based on the service type ID
    $call_types_result = mysqli_query($conn, "SELECT * FROM call_types WHERE service_type_id = $service_type_id");

    // Generate the options for the Call Type dropdown
    if (mysqli_num_rows($call_types_result) > 0) {
        while ($row = mysqli_fetch_assoc($call_types_result)) {
            echo "<option value='{$row['id']}'>{$row['call_type']}</option>";
        }
    } else {
        echo "<option value=''>No Call Types Available</option>";
    }
}
?>
