<?php
// Include database connection
include('includes/db.php');

// Check if the call type ID is provided
if (isset($_POST['call_type_id'])) {
    $call_type_id = $_POST['call_type_id'];

    // Fetch the service types associated with the selected call type
    $query = "SELECT * FROM service_types WHERE call_type_id = '$call_type_id'";
    $result = mysqli_query($conn, $query);

    // Check if there are service types
    if (mysqli_num_rows($result) > 0) {
        // Loop through the results and generate the dropdown options
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['id']}'>{$row['service_type']}</option>";
        }
    } else {
        echo "<option value=''>No Service Types found</option>";
    }
}
?>
