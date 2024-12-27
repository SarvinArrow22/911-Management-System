<?php
session_start();
include('includes/db.php'); // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
// If the user is logged in, display their ID.
if (isset($_SESSION['user_id'])) {

}
?>


<?php
// Include database connection
// Example: include 'db_connection.php';

// Initialize the response variable for call types
$call_types = [];

// If the request is an AJAX call (POST or GET request with service_type_id)
if (isset($_POST['service_type_id'])) {
    $service_type_id = (int)$_POST['service_type_id'];
    // Fetch call types based on the selected service type ID
    $call_types_result = mysqli_query($conn, "SELECT * FROM call_types WHERE service_type_id = $service_type_id");
    while ($row = mysqli_fetch_assoc($call_types_result)) {
        $call_types[] = $row;
    }
    echo json_encode($call_types); // Return the call types as JSON
    exit;
}

// Fetch all service types for the initial dropdown
$service_types_result = mysqli_query($conn, "SELECT * FROM service_types");

?>

<!DOCTYPE html>
<html lang="en">
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
<body class="bg-light">

    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center p-3 bg-primary text-white shadow-sm">
        <h1 class="h4 mb-0">User Dashboard</h1>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <?= $_SESSION['full_name'] ?>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <!-- Main Content -->
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

        <!-- Main Content -->
        <div class="container-fluid p-4">
            <div class="row mb-4">
                <!-- Total Calls Summary Cards -->
                <?php
                    // SQL query to count the total calls made today
                    $query = "
                        SELECT COUNT(*) AS total_calls_today 
                        FROM call_logs cl
                        WHERE DATE(cl.call_date) = CURDATE()
                    ";

                    // Execute the query
                    $result = mysqli_query($conn, $query);

                    // Fetch the result
                    $row = mysqli_fetch_assoc($result);
                    $totalCallsToday = $row['total_calls_today'];

                    // If there are no calls today, set count to 0
                    if ($totalCallsToday == NULL) {
                        $totalCallsToday = 0;
                    }
                ?>

                
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Total Calls Today</h5>
                            <p class="h3" id="totalCallsToday"><?php echo $totalCallsToday; ?></p>
                        </div>
                    </div>
                </div>


                <?php

                // Check if the user is logged in (session variable is set)
                if (isset($_SESSION['user_id'])) {
                    // Get the logged-in user's ID from the session
                    $user_id = $_SESSION['user_id'];

                    // Sanitize user input to prevent SQL injection (using mysqli_real_escape_string)
                    $user_id = mysqli_real_escape_string($conn, $user_id);

                    // SQL query to count the total calls made by the specific agent this month
                    $query = "
                        SELECT COUNT(*) AS total_calls_this_month 
                        FROM call_logs cl
                        WHERE MONTH(cl.call_date) = MONTH(CURDATE()) 
                        AND cl.agent_id = '$user_id'  -- filter by agent_id
                    ";

                    // Execute the query
                    $result = mysqli_query($conn, $query);

                    // Check if the query was successful
                    if ($result) {
                        // Fetch the result
                        $row = mysqli_fetch_assoc($result);
                        $totalCallsThisMonth = $row['total_calls_this_month'];

                        // If no calls are found for this month, set the count to 0
                        if ($totalCallsThisMonth == NULL) {
                            $totalCallsThisMonth = 0;
                        }
                    } else {
                        // If query fails, set total calls to 0
                        $totalCallsThisMonth = 0;
                    }
                } else {
                    // If no user is logged in, set the total calls to 0
                    $totalCallsThisMonth = 0;
                }
                ?>

                <!-- HTML to display the total calls made by the agent this month -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Total Calls This Month</h5>
                            <p class="h3" id="totalCallsMonth"><?php echo $totalCallsThisMonth; ?></p>
                        </div>
                    </div>
                </div>


                                
                <?php


                // Check if the user is logged in (session variable is set)
                if (isset($_SESSION['user_id'])) {
                    // Get the logged-in user's ID from the session
                    $user_id = $_SESSION['user_id'];

                    // Sanitize user input to prevent SQL injection (using mysqli_real_escape_string)
                    $user_id = mysqli_real_escape_string($conn, $user_id);

                    // SQL query to count the total calls made by the specific agent this year
                    $query = "
                        SELECT COUNT(*) AS total_calls_this_year 
                        FROM call_logs cl
                        WHERE YEAR(cl.call_date) = YEAR(CURDATE()) 
                        AND cl.agent_id = '$user_id'  -- filter by agent_id
                    ";

                    // Execute the query
                    $result = mysqli_query($conn, $query);

                    // Check if the query was successful
                    if ($result) {
                        // Fetch the result
                        $row = mysqli_fetch_assoc($result);
                        $totalCallsThisYear = $row['total_calls_this_year'];

                        // If no calls are found for this year, set the count to 0
                        if ($totalCallsThisYear == NULL) {
                            $totalCallsThisYear = 0;
                        }
                    } else {
                        // If query fails, set total calls to 0
                        $totalCallsThisYear = 0;
                    }
                } else {
                    // If no user is logged in, set the total calls to 0
                    $totalCallsThisYear = 0;
                }
                ?>

                <!-- HTML to display the total calls made by the agent this year -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Total Calls This Year</h5>
                            <p class="h3" id="totalCallsYear"><?php echo $totalCallsThisYear; ?></p>
                        </div>
                    </div>
                </div>



                <?php

                // Check if the user is logged in (session variable is set)
                if (isset($_SESSION['user_id'])) {
                    // Get the logged-in user's ID from the session
                    $user_id = $_SESSION['user_id'];

                    // Sanitize user input to prevent SQL injection (using mysqli_real_escape_string)
                    $user_id = mysqli_real_escape_string($conn, $user_id);

                    // SQL query to count the total calls made by the specific agent this week
                    $query = "
                        SELECT COUNT(*) AS total_calls_this_week 
                        FROM call_logs cl
                        WHERE YEAR(cl.call_date) = YEAR(CURDATE())  -- filter by the current year
                        AND WEEK(cl.call_date, 1) = WEEK(CURDATE(), 1)  -- filter by the current week
                        AND cl.agent_id = '$user_id'  -- filter by agent_id
                    ";

                    // Execute the query
                    $result = mysqli_query($conn, $query);

                    // Check if the query was successful
                    if ($result) {
                        // Fetch the result
                        $row = mysqli_fetch_assoc($result);
                        $totalCallsThisWeek = $row['total_calls_this_week'];

                        // If no calls are found for this week, set the count to 0
                        if ($totalCallsThisWeek == NULL) {
                            $totalCallsThisWeek = 0;
                        }
                    } else {
                        // If query fails, set total calls to 0
                        $totalCallsThisWeek = 0;
                    }
                } else {
                    // If no user is logged in, set the total calls to 0
                    $totalCallsThisWeek = 0;
                }
                ?>

                <!-- HTML to display the total calls made by the agent this week -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Total Calls This Week</h5>
                            <p class="h3" id="totalCallsWeek"><?php echo $totalCallsThisWeek; ?></p>
                        </div>
                    </div>
                </div>

            </div>

<!-- Call Log Data Table -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title mb-4">Call Log</h5>

        <!-- Button to Open Add New Log Modal -->
        <div class="d-flex justify-content-between align-items-center">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLogModal">
                <i class="bi bi-plus-circle"></i> Add New Log
            </button>
        </div>

        <!-- Tab navigation -->
        <ul class="nav nav-tabs" id="caseTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pending-case-tab" data-bs-toggle="tab" href="#pending-case" role="tab" aria-controls="pending-case" aria-selected="true">Pending Case</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="closed-case-tab" data-bs-toggle="tab" href="#closed-case" role="tab" aria-controls="closed-case" aria-selected="false">Closed Case</a>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content mt-3" id="caseTabsContent">
            <!-- Pending Case Tab -->
            <div class="tab-pane fade show active" id="pending-case" role="tabpanel" aria-labelledby="pending-case-tab">
                <!-- Call log table for Pending Cases -->
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-striped" id="callLogTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th class="agent-id-column">Agent ID</th>
                                <th>Type of Service</th>
                                <th>Call Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Contact Number</th>
                                <th>Count</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query for Pending Cases where status = 'pending'
                            $query = "
                                SELECT cl.id, cl.agent_id, st.service_type, ct.call_type, cl.call_date, cl.call_time,
                                       cl.contact_number, cl.call_count, cl.name, cl.age, cl.location, cl.status
                                FROM call_logs cl
                                LEFT JOIN service_types st ON cl.type_of_service = st.id
                                LEFT JOIN call_types ct ON cl.call_type = ct.id
                                WHERE cl.agent_id = '$user_id' AND cl.status = 'pending_case'"; // Filter by 'pending' status
                            
                            $result = mysqli_query($conn, $query);
                            $contactNumberCounts = [];
                            if (mysqli_num_rows($result) > 0) {
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $contactNumber = $row['contact_number'];
                                    if (isset($contactNumberCounts[$contactNumber])) {
                                        $contactNumberCounts[$contactNumber]++;
                                    } else {
                                        $contactNumberCounts[$contactNumber] = 1;
                                    }

                                    echo "<tr class='clickable-row' data-id='{$row['id']}' data-agent-id='{$row['agent_id']}' data-service-type='{$row['service_type']}' data-call-type='{$row['call_type']}' data-call-date='{$row['call_date']}' data-call-time='{$row['call_time']}' data-contact-number='{$row['contact_number']}' data-call-count='{$contactNumberCounts[$contactNumber]}' data-name='{$row['name']}' data-age='{$row['age']}' data-location='{$row['location']}'>
                                            <td>{$no}</td>
                                            <td class='agent-id-column'>{$row['agent_id']}</td>
                                            <td>{$row['service_type']}</td>
                                            <td>{$row['call_type']}</td>
                                            <td>{$row['call_date']}</td>
                                            <td>{$row['call_time']}</td>
                                            <td>{$row['contact_number']}</td>
                                            <td>{$contactNumberCounts[$contactNumber]}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['age']}</td>
                                            <td>{$row['location']}</td>
                                        </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='11' class='text-center'>No pending cases found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Closed Case Tab -->
            <div class="tab-pane fade" id="closed-case" role="tabpanel" aria-labelledby="closed-case-tab">
                <!-- Call log table for Closed Cases -->
                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered table-striped" id="callLogTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th class="agent-id-column">Agent ID</th>
                                <th>Type of Service</th>
                                <th>Call Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Contact Number</th>
                                <th>Count</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query for Closed Cases where status = 'closed'
                            $query = "
                                SELECT cl.id, cl.agent_id, st.service_type, ct.call_type, cl.call_date, cl.call_time,
                                       cl.contact_number, cl.call_count, cl.name, cl.age, cl.location, cl.status
                                FROM call_logs cl
                                LEFT JOIN service_types st ON cl.type_of_service = st.id
                                LEFT JOIN call_types ct ON cl.call_type = ct.id
                                WHERE cl.agent_id = '$user_id' AND cl.status = 'closed_case'"; // Filter by 'closed' status
                            
                            $result = mysqli_query($conn, $query);
                            $contactNumberCounts = [];
                            if (mysqli_num_rows($result) > 0) {
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $contactNumber = $row['contact_number'];
                                    if (isset($contactNumberCounts[$contactNumber])) {
                                        $contactNumberCounts[$contactNumber]++;
                                    } else {
                                        $contactNumberCounts[$contactNumber] = 1;
                                    }

                                    echo "<tr class='clickable-row' data-id='{$row['id']}' data-agent-id='{$row['agent_id']}' data-service-type='{$row['service_type']}' data-call-type='{$row['call_type']}' data-call-date='{$row['call_date']}' data-call-time='{$row['call_time']}' data-contact-number='{$row['contact_number']}' data-call-count='{$contactNumberCounts[$contactNumber]}' data-name='{$row['name']}' data-age='{$row['age']}' data-location='{$row['location']}'>
                                            <td>{$no}</td>
                                            <td class='agent-id-column'>{$row['agent_id']}</td>
                                            <td>{$row['service_type']}</td>
                                            <td>{$row['call_type']}</td>
                                            <td>{$row['call_date']}</td>
                                            <td>{$row['call_time']}</td>
                                            <td>{$row['contact_number']}</td>
                                            <td>{$contactNumberCounts[$contactNumber]}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['age']}</td>
                                            <td>{$row['location']}</td>
                                        </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='11' class='text-center'>No closed cases found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Bootstrap Modal to Edit/Show Details -->
<div class="modal fade" id="callLogModal" tabindex="-1" aria-labelledby="callLogModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="callLogModalLabel">Call Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="callingModal" action="editmodal.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="modalId"> <!-- Hidden field to store the ID -->

                    <!-- Service Type Label and Input -->
                    <div style="display: flex;">
                        <label for="">Service Type</label>
                        <span id="serviceTypeText" style="display: none;">Service Type</span> <!-- Text for select service type -->
                    </div>
                   
                    <div class="mb-3" style="display: flex; width: 100%;">
                        <input type="text" id="modalServiceType" name="service_type" class="form-control" required placeholder="Enter Service Type" readonly>
                        
                        <!-- Service Type Dropdown (hidden by default) -->
                        <select id="modalServiceTypeSelect" name="service_type" class="form-control" style="display: none;">
                            <?php
                            // Fetch service types from the database
                            $service_types_result = mysqli_query($conn, "SELECT * FROM service_types");
                            while ($row = mysqli_fetch_assoc($service_types_result)) {
                                echo "<option value='{$row['id']}'>{$row['service_type']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Call Type Label and Input -->
                    <div style="display: flex;">
                        <label for="">Call Type</label>
                        <span id="callTypeText" style="display: none;">Call Type</span> <!-- Text for select call type -->
                    </div>

                    <!-- Call Type Dropdown -->
                    <div class="mb-3" style="display: flex; width: 100%;">
                        <input type="text" id="modalCallType" name="call_type" class="form-control" required placeholder="Enter Call Type" readonly>
                        
                        <!-- Call Type Dropdown (hidden by default) -->
                        <select id="modalCallTypeSelect" name="call_type" class="form-control" style="display: none;" disabled>
                            <option value="">Select Call Type</option>
                        </select>
                    </div>

                    <!-- Call Date -->
                    <div class="mb-3">
                        <label for="modalCallDate" class="form-label">Call Date</label>
                        <input type="text" class="form-control" name="call_date" id="modalCallDate" required readonly>
                    </div>

                    <!-- Call Time -->
                    <div class="mb-3">
                        <label for="modalCallTime" class="form-label">Call Time</label>
                        <input type="text" class="form-control" name="call_time" id="modalCallTime" required readonly>
                    </div>

                    <!-- Contact Number -->
                    <div class="mb-3">
                        <label for="modalContactNumber" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" name="contact_number" id="modalContactNumber" required readonly>
                    </div>

                    <!-- Count -->
                    <div class="mb-3">
                        <label for="modalCount" class="form-label">Count</label>
                        <input type="text" class="form-control" name="count" id="modalCount" required readonly>
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="modalName" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="modalName" required readonly>
                    </div>

                    <!-- Age -->
                    <div class="mb-3">
                        <label for="modalAge" class="form-label">Age</label>
                        <input type="text" class="form-control" name="age" id="modalAge" required readonly>
                    </div>

                    <!-- Location -->
                    <div class="mb-3">
                        <label for="modalLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" name="location" id="modalLocation" required readonly>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="editButton">Edit</button>
                    <!-- Save and Cancel buttons hidden initially -->
                    <button type="submit" class="btn btn-primary" id="saveButton" style="display: none;">Save</button>
                    <button type="button" class="btn btn-secondary" id="cancelButton" style="display: none;">Cancel</button>
                    <!-- Delete and Close buttons -->
                    <button type="submit" class="btn btn-danger" id="deleteButton">Delete</button>
                    <button type="button" class="btn btn-secondary" id="closeButton" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript to enable the Edit functionality -->
<script>
// When the Edit button is clicked
document.getElementById('editButton').addEventListener('click', function() {
    // Show the "Select Service Type" text and the dropdown
    document.getElementById('serviceTypeText').style.display = 'inline';  // Show the label "Select Service Type"
    document.getElementById('modalServiceTypeSelect').style.display = 'inline';  // Show the dropdown
    document.getElementById('modalServiceType').style.display = 'none';  // Hide the text input

    // Show the "Select Call Type" text and dropdown
    document.getElementById('callTypeText').style.display = 'inline';  // Show the label "Select Call Type"
    document.getElementById('modalCallTypeSelect').style.display = 'inline';  // Show the dropdown
    document.getElementById('modalCallType').style.display = 'none';  // Hide the text input

    // Enable all the input fields by removing the readonly attribute
    let inputs = document.querySelectorAll('#callLogModal input');
    inputs.forEach(input => input.removeAttribute('readonly'));

    // Enable the dropdowns by removing the disabled attribute
    document.getElementById('modalServiceTypeSelect').removeAttribute('disabled');
    document.getElementById('modalCallTypeSelect').removeAttribute('disabled');

    // Hide the Edit button
    document.getElementById('editButton').style.display = 'none';

    // Show the Save and Cancel buttons
    document.getElementById('saveButton').style.display = 'inline-block';
    document.getElementById('cancelButton').style.display = 'inline-block';

    // Hide the Delete and Close buttons
    document.getElementById('deleteButton').style.display = 'none';
    document.getElementById('closeButton').style.display = 'none';
});

// When the Cancel button is clicked
document.getElementById('cancelButton').addEventListener('click', function() {
    // Hide the "Select Service Type" text and the dropdown
    document.getElementById('serviceTypeText').style.display = 'none'; // Hide "Select Service Type"
    document.getElementById('modalServiceTypeSelect').style.display = 'none'; // Hide the dropdown
    document.getElementById('modalServiceType').style.display = 'inline';  // Show the text input

    // Hide the "Select Call Type" text and the dropdown
    document.getElementById('callTypeText').style.display = 'none'; // Hide "Select Call Type"
    document.getElementById('modalCallTypeSelect').style.display = 'none'; // Hide the dropdown
    document.getElementById('modalCallType').style.display = 'inline';  // Show the text input

    // Disable all input fields by adding the readonly attribute
    let inputs = document.querySelectorAll('#callLogModal input');
    inputs.forEach(input => input.setAttribute('readonly', 'true'));

    // Disable the dropdowns by adding the disabled attribute
    document.getElementById('modalServiceTypeSelect').setAttribute('disabled', 'true');
    document.getElementById('modalCallTypeSelect').setAttribute('disabled', 'true');

    // Hide the Save and Cancel buttons
    document.getElementById('saveButton').style.display = 'none';
    document.getElementById('cancelButton').style.display = 'none';

    // Show the Edit button
    document.getElementById('editButton').style.display = 'inline-block';

    // Show the Delete and Close buttons
    document.getElementById('deleteButton').style.display = 'inline-block';
    document.getElementById('closeButton').style.display = 'inline-block';
});

// Dynamically load call types based on the selected service type (AJAX code)
document.getElementById('modalServiceTypeSelect').addEventListener('change', function() {
    var serviceTypeId = this.value;
    
    // If no service type is selected, clear the call type dropdown
    if (serviceTypeId === "") {
        document.getElementById('modalCallTypeSelect').innerHTML = '<option value="">Select Call Type</option>';
        return;
    }

    // Send the selected service type ID to the server using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_call_types.php?service_type_id=' + serviceTypeId, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Populate the Call Type dropdown with the received data
            document.getElementById('modalCallTypeSelect').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
});
</script>




    </div>
</div>







          

  
<!-- Add New Log Modal -->
<div class="modal fade" id="addLogModal" tabindex="-1" aria-labelledby="addLogModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLogModalLabel">Add New Call Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="logForm" method="post" action="process_add_log.php">
                    <!-- Hidden User ID field -->
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                    <!-- Service Type Dropdown -->
                    <div class="mb-3">
                        <label for="service_type">Service Type</label>
                        <select id="service_type" name="service_type" class="form-control">
                            <option value="">Select Service Type</option>
                            <?php
                            // Fetch service types from the database
                            $service_types_result = mysqli_query($conn, "SELECT * FROM service_types");
                            while ($row = mysqli_fetch_assoc($service_types_result)) {
                                echo "<option value='{$row['id']}'>{$row['service_type']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Call Type Dropdown -->
                    <div class="mb-3">
                        <label for="call_type">Call Type</label>
                        <select id="call_type" name="call_type" class="form-control">
                            <option value="">Select Call Type</option>
                        </select>
                    </div>

                    <script>
                        // JavaScript (AJAX) to update the Call Type dropdown based on the selected Service Type
                        document.getElementById('service_type').addEventListener('change', function() {
                            var serviceTypeId = this.value;
                            
                            // If no service type is selected, clear the call type dropdown
                            if (serviceTypeId === "") {
                                document.getElementById('call_type').innerHTML = '<option value="">Select Call Type</option>';
                                return;
                            }

                            // Send the selected service type ID to the server using AJAX
                            var xhr = new XMLHttpRequest();
                            xhr.open('GET', 'get_call_types.php?service_type_id=' + serviceTypeId, true);
                            xhr.onload = function() {
                                if (xhr.status === 200) {
                                    // Populate the Call Type dropdown with the received data
                                    document.getElementById('call_type').innerHTML = xhr.responseText;
                                }
                            };
                            xhr.send();
                        });
                    </script>

                    <!-- Other Form Fields -->
                    <div class="mb-3">
                        <label for="contactNumber" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contactNumber" name="contactNumber">
                    </div>

                    <div class="mb-3">
                        <label for="time" class="form-label">Time</label> 
                        <input type="text" class="form-control" id="time" name="call_time"> 
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>

                    <div class="mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" name="age">
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location">
                    </div>

                    <div class="mb-3">
                        <label for="reason_of_call">Reason of Call</label>
                        <input type="text" class="form-control" id="reason_of_call" name="reason_of_call">
                    </div>

                    <div class="mb-3">
                        <label for="action_taken">Action Taken</label>
                        <input type="text" class="form-control" id="action_taken" name="action_taken">
                    </div>

                    <div class="mb-3">
                        <label for="remarks">Remarks</label>
                        <input type="text" class="form-control" id="remarks" name="remarks">
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="pending_case">pending_case</option>
                            <option value="closed_case">closed_case</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Save Log</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



            <!-- Client Feedback Section -->
            <!-- <div class="text-center">
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                                <i class="bi bi-pencil-square"></i> Submit Feedback
                            </button>
                        </div>
                    </div>
                </div> -->

    <!-- Bootstrap JS and necessary Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Additional JS for Dynamic Call Type Dropdown and Feedback Submission -->
    <script>
                // Handle Feedback Form Submission
            document.getElementById('feedbackForm').addEventListener('submit', function (e) {
                e.preventDefault();
                // Send form data to the server via AJAX or other methods
                // For now, just display a confirmation
                alert('Thank you for your feedback!');
                $('#feedbackModal').modal('hide');
            });
      


            // Dynamically update Call Type options based on selected Service Type
            document.getElementById('service_type').addEventListener('change', function() {
                var serviceTypeId = this.value;
                var callTypeSelect = document.getElementById('call_type');
                
                // Clear existing Call Type options
                callTypeSelect.innerHTML = '<option value="">Select Call Type</option>';

                // Fetch Call Types based on selected Service Type
                if (serviceTypeId) {
                    fetch('get_call_types.php?service_type_id=' + serviceTypeId)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(callType => {
                                var option = document.createElement('option');
                                option.value = callType.id;
                                option.textContent = callType.call_type;
                                callTypeSelect.appendChild(option);
                            });
                        });
                }
            });
    </script>
</body>
</html>


<script>
        // JavaScript to dynamically load call types based on selected service type
        document.getElementById('service_type').addEventListener('change', function() {
            var serviceTypeId = this.value;
            
            // Only fetch call types if a valid service type is selected
            if (serviceTypeId) {
                // Make an AJAX request to fetch call types related to the selected service type
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'records.php?service_type_id=' + serviceTypeId, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var callTypes = JSON.parse(xhr.responseText);
                        var callTypeSelect = document.getElementById('call_type');
                        callTypeSelect.innerHTML = '<option value="">Select Call Type</option>'; // Reset call type dropdown
                        
                        // Populate the call type dropdown with the fetched options
                        callTypes.forEach(function(callType) {
                            var option = document.createElement('option');
                            option.value = callType.id;
                            option.textContent = callType.call_type;
                            callTypeSelect.appendChild(option);
                        });
                    }
                };
                xhr.send();
            } else {
                // If no service type is selected, reset the call type dropdown
                document.getElementById('call_type').innerHTML = '<option value="">Select Call Type</option>';
            }
        });
    </script>

<script>
   // Event listener to populate modal when a row is clicked
document.querySelectorAll('.clickable-row').forEach(row => {
    row.addEventListener('click', function () {
        // Get the data from the clicked row's data attributes
        var id = this.getAttribute('data-id');
        var serviceType = this.getAttribute('data-service-type');
        var callType = this.getAttribute('data-call-type');
        var callDate = this.getAttribute('data-call-date');
        var callTime = this.getAttribute('data-call-time');
        var contactNumber = this.getAttribute('data-contact-number');
        var count = this.getAttribute('data-call-count');
        var name = this.getAttribute('data-name');
        var age = this.getAttribute('data-age');
        var location = this.getAttribute('data-location');

        // Populate the modal with the row data
        document.getElementById('modalId').value = id;
        document.getElementById('modalServiceType').value = serviceType;
        document.getElementById('modalCallDate').value = callDate;
        document.getElementById('modalCallTime').value = callTime;
        document.getElementById('modalContactNumber').value = contactNumber;
        document.getElementById('modalCount').value = count;
        document.getElementById('modalName').value = name;
        document.getElementById('modalAge').value = age;
        document.getElementById('modalLocation').value = location;

        // Trigger change event to update call types based on the selected service type
        document.getElementById('modalServiceType').dispatchEvent(new Event('change'));

        // Pre-select call type (if available)
        document.getElementById('modalCallType').value = callType;

        // Show the modal
        var myModal = new bootstrap.Modal(document.getElementById('callLogModal'));
        myModal.show();
    });
});


    // JavaScript (AJAX) to update the Call Type dropdown based on the selected Service Type
    document.getElementById('modalServiceType').addEventListener('change', function() {
        var serviceTypeId = this.value;

        // If no service type is selected, clear the call type dropdown
        if (serviceTypeId === "") {
            document.getElementById('modalCallType').innerHTML = '<option value="">Select Call Type</option>';
            return;
        }

        // Send the selected service type ID to the server using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_call_types.php?service_type_id=' + serviceTypeId, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Populate the Call Type dropdown with the received data
                document.getElementById('modalCallType').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    });
</script>

