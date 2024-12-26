<?php
// Include the database connection
include('includes/db.php');

// Check if the 'id' parameter is passed in the URL (for identifying the record)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query to fetch the data from the database based on the provided ID
    $query = "
        SELECT cl.id,
               cl.agent_id,
               st.service_type,
               ct.call_type,
               cl.call_date,
               cl.call_time,
               cl.contact_number,
               cl.call_count,
               cl.name,
               cl.age,
               cl.location,
               cl.reason_of_call,
               cl.actions_taken,
               cl.remarks
        FROM call_logs cl
        LEFT JOIN service_types st ON cl.type_of_service = st.id
        LEFT JOIN call_types ct ON cl.call_type = ct.id
        WHERE cl.id = '$id'
    ";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Fetch the record data
        $row = mysqli_fetch_assoc($result);
    } else {
        // If no record is found, redirect or show an error
        echo "Call log not found!";
        exit();
    }
} else {
    // If 'id' is not passed, redirect to another page
    header("Location: admin-settings.php?error=No ID provided.");
    exit();
}

// If the form is submitted to update the log
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated_service_type = mysqli_real_escape_string($conn, $_POST['service_type']);
    $updated_call_type = mysqli_real_escape_string($conn, $_POST['call_type']);
    $updated_call_time = mysqli_real_escape_string($conn, $_POST['call_time']);
    $updated_contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $updated_name = mysqli_real_escape_string($conn, $_POST['name']);
    $updated_age = mysqli_real_escape_string($conn, $_POST['age']);
    $updated_location = mysqli_real_escape_string($conn, $_POST['location']);
    $updated_reason_of_call = mysqli_real_escape_string($conn, $_POST['reason_of_call']);
    $updated_action_taken = mysqli_real_escape_string($conn, $_POST['action_taken']);
    $updated_remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    // Update the record in the database
    $update_query = "
        UPDATE call_logs
        SET type_of_service = '$updated_service_type',
            call_type = '$updated_call_type',
            call_time = '$updated_call_time',
            contact_number = '$updated_contact_number',
            name = '$updated_name',
            age = '$updated_age',
            location = '$updated_location',
            reason_of_call = '$updated_reason_of_call',
            actions_taken = '$updated_action_taken',
            remarks = '$updated_remarks'
        WHERE id = '$id'
    ";

    if (mysqli_query($conn, $update_query)) {
        // Redirect to another page or show success
        header("Location: user_dashboard.php?success=Call log updated successfully");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet"> <!-- Custom Styles -->
    <style>
        /* Ensure the header does not overlap with the side panel */
        body {
            margin: 0;
            padding: 0;
        }

        /* Adjust the header's margin-bottom to add space between the header and the side panel */
        header {
            margin-bottom: 0px; /* You can adjust this value based on your design preference */
        }

        /* Adjust the left margin for the main content to account for the side panel width */
        .container-fluid {
            margin-left: 50px; /* This should match the width of your side panel */
        }

        /* Optional: Add more space to the top of the content if needed */
        .container-fluid .row {
            margin-top: 0px;
        }
        /* Adjust modal width to make it landscape */
        .modal-dialog.modal-lg {
            max-width: 80%;  /* You can adjust this percentage */
        }

        /* Add space for form fields inside the modal */
        .modal-body .row {
            margin-bottom: 15px;
        }

        /* Ensure the modal content is not too tight */
        .modal-content {
            padding: 10px;
        }

        /* Style input fields to make the layout clear */
        .modal-body input,
        .modal-body select,
        .modal-body textarea {
            margin-bottom: 10px;
        }

        /* Adjust button styling for consistency */
        .modal-footer .btn {
            padding: 10px 20px;
        }
        .side-panel .logo {
            display: block;
            margin: 0 auto;
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
        }
        .agent-id-column {
            display: none;
        }

    </style>

</head>
 <div class="d-flex">
        <!-- Side Panel -->
        <div class="bg-dark text-white p-4 shadow-lg side-panel" style="width: 250px; height: 100vh;">
            <div class="text-center mb-4">
                <img src="assets/images/911 Official Logo.webp" alt="Logo" class="logo" >
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-3">
                    <a class="nav-link text-white" href="user_dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link text-white" href="user_call_log.php"><i class="bi bi-file-earmark-medical"></i> Call Logs</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link text-white" href="user_feedback.php"><i class="bi bi-pencil-square"></i> Feedback</a>
                </li>
            </ul>
        </div>

        

        <div class="container-fluid p-4 ">


            <!-- Display the form to update the call log -->
            <form style="overflow-y: scroll; height: 40rem;"  method="post" action="edit.php?id=<?php echo $id; ?>">
                <div class="mb-3">
                    <label for="service_type" class="form-label">Service Type</label>
                    <select id="service_type" name="service_type" class="form-control" required>
                        <option value="">Select Service Type</option>
                        <?php
                        // Fetch service types for the dropdown
                        $service_types_result = mysqli_query($conn, "SELECT * FROM service_types");
                        while ($service_row = mysqli_fetch_assoc($service_types_result)) {
                            $selected = $row['service_type'] == $service_row['service_type'] ? "selected" : "";
                            echo "<option value='{$service_row['id']}' $selected>{$service_row['service_type']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="call_type" class="form-label">Call Type</label>
                    <select id="call_type" name="call_type" class="form-control" required>
                        <option value="">Select Call Type</option>
                        <?php
                        // Fetch call types for the dropdown
                        $call_types_result = mysqli_query($conn, "SELECT * FROM call_types");
                        while ($call_row = mysqli_fetch_assoc($call_types_result)) {
                            $selected = $row['call_type'] == $call_row['call_type'] ? "selected" : "";
                            echo "<option value='{$call_row['id']}' $selected>{$call_row['call_type']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="call_date" class="form-label">Call Date</label>
                    <input type="date" class="form-control" id="call_date" name="call_date" value="<?php echo $row['call_date']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="call_time" class="form-label">Call Time</label>
                    <input type="time" class="form-control" id="call_time" name="call_time" value="<?php echo $row['call_time']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo $row['contact_number']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age" value="<?php echo $row['age']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo $row['location']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="reason_of_call" class="form-label">Reason of Call</label>
                    <textarea class="form-control" id="reason_of_call" name="reason_of_call" required><?php echo $row['reason_of_call']; ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="actions_taken" class="form-label">Actions Taken</label>
                    <textarea class="form-control" id="actions_taken" name="actions_taken" required><?php echo $row['actions_taken']; ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks"><?php echo $row['remarks']; ?></textarea>
                </div>

                
            </form>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>

        </div>