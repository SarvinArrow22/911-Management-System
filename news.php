<?php
session_start();
include('includes/db.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logged-in user's information
$user_id = $_SESSION['user_id'];
$query = "SELECT team FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Determine which table to query based on the user's team
$team = $user['team'];

if ($team == 'alpha') {
    $table_name = 'alpha_tbl';
} elseif ($team == 'bravo') {
    $table_name = 'bravo_tbl';
} elseif ($team == 'charlie') {
    $table_name = 'charlie_tbl';
} else {
    echo "Unknown team. Please contact the administrator.";
    exit();
}

// Fetch records from the relevant table for the logged-in user (agent_id)
$query = "SELECT * FROM $table_name WHERE agent_id = $user_id";
$result = mysqli_query($conn, $query);

?>


<?php
// Function to fetch the call counts for the user based on the team
function getCallCountByTeam($conn, $user_id, $team, $time_period) {
    // Dynamically set the table name based on the team
    $tableName = '';
    switch ($team) {
        case 'alpha':
            $tableName = 'alpha_tbl';
            break;
        case 'bravo':
            $tableName = 'bravo_tbl';
            break;
        case 'charlie':
            $tableName = 'charlie_tbl';
            break;
        default:
            // If the team is not valid, default to call_logs
            $tableName = 'call_logs';
            break;
    }

    // Create base query
    $query = "SELECT COUNT(*) AS total_calls FROM $tableName cl
              JOIN users u ON cl.agent_id = u.id 
              WHERE u.team = ? AND cl.agent_id = ? ";

    // Add time condition based on the time period
    switch ($time_period) {
        case 'today':
            $query .= "AND DATE(cl.call_date) = CURDATE()";
            break;
        case 'this_month':
            $query .= "AND MONTH(cl.call_date) = MONTH(CURDATE())";
            break;
        case 'this_year':
            $query .= "AND YEAR(cl.call_date) = YEAR(CURDATE())";
            break;
        case 'this_week':
            $query .= "AND YEAR(cl.call_date) = YEAR(CURDATE()) 
                        AND WEEK(cl.call_date, 1) = WEEK(CURDATE(), 1)";
            break;
        default:
            return 0; // Invalid period
    }

    // Prepare the statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        // If the statement preparation fails, log the error
        error_log("Error preparing statement: " . mysqli_error($conn));
        return 0;
    }

    // Bind team and user_id parameters
    mysqli_stmt_bind_param($stmt, "si", $team, $user_id);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        // Fetch the result and return the count
        $row = mysqli_fetch_assoc($result);
        return $row['total_calls'] ?? 0;
    } else {
        // If execution fails, log the error
        error_log("Error executing query: " . mysqli_stmt_error($stmt));
        return 0;
    }
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
<!-- Meta tags  -->
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

<title>911 Emergency System</title>
<link rel="icon" type="image/webp" href="assets/images/911 Official Logo.webp">

<!-- CSS Assets -->
<link rel="stylesheet" href="assets/css/app.css">

<!-- Modal Styling -->
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 1% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
    }

    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

<!-- Javascript Assets -->
<script src="assets/js/app.js" defer=""></script>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
<link href="css2?family=Inter:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
<script>
    /**
    * THIS SCRIPT REQUIRED FOR PREVENT FLICKERING IN SOME BROWSERS
    */
    localStorage.getItem("_x_darkMode_on") === "true" &&
    document.documentElement.classList.add("dark");
</script>
</head>
  
<body x-data="" class="is-header-blur" x-bind="$store.global.documentBody">
    <!-- App preloader-->
    <div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-slate-50 dark:bg-navy-900">
      <div class="app-preloader-inner relative inline-block size-48"></div>
    </div>
    <!-- Page Wrapper -->
    <div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" x-cloak="">
        <!-- Sidebar -->
        <div class="sidebar print:hidden">
            <!-- Main Sidebar -->
            <div class="main-sidebar">
                <div class="flex h-full w-full flex-col items-center border-r border-slate-150 bg-white dark:border-navy-700 dark:bg-navy-800">
                    <!-- Application Logo -->
                    <div class="flex pt-4">
                        <a href="user_dashboard2.php">
                            <img class="size-11 transition-transform duration-500 ease-in-out hover:rotate-[360deg]" src="assets/images/911 Official Logo.webp" alt="logo">
                        </a>
                    </div>

                    <!-- Main Sections Links -->
                    <div class="is-scrollbar-hidden flex grow flex-col space-y-4 overflow-y-auto pt-6">
                        <!-- Dashobards -->
                        <a href="user_dashboard2.php" class="flex size-11 items-center justify-center rounded-lg bg-primary/10 text-primary outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:bg-navy-600 dark:text-accent-light dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90" x-tooltip.placement.right="'Dashboard'">
                            <svg class="size-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24">
                            <path fill="currentColor" fill-opacity=".3" d="M5 14.059c0-1.01 0-1.514.222-1.945.221-.43.632-.724 1.453-1.31l4.163-2.974c.56-.4.842-.601 1.162-.601.32 0 .601.2 1.162.601l4.163 2.974c.821.586 1.232.88 1.453 1.31.222.43.222.935.222 1.945V19c0 .943 0 1.414-.293 1.707C18.414 21 17.943 21 17 21H7c-.943 0-1.414 0-1.707-.293C5 20.414 5 19.943 5 19v-4.94Z"></path>
                            <path fill="currentColor" d="M3 12.387c0 .267 0 .4.084.441.084.041.19-.04.4-.204l7.288-5.669c.59-.459.885-.688 1.228-.688.343 0 .638.23 1.228.688l7.288 5.669c.21.163.316.245.4.204.084-.04.084-.174.084-.441v-.409c0-.48 0-.72-.102-.928-.101-.208-.291-.355-.67-.65l-7-5.445c-.59-.459-.885-.688-1.228-.688-.343 0-.638.23-1.228.688l-7 5.445c-.379.295-.569.442-.67.65-.102.208-.102.448-.102.928v.409Z"></path>
                            <path fill="currentColor" d="M11.5 15.5h1A1.5 1.5 0 0 1 14 17v3.5h-4V17a1.5 1.5 0 0 1 1.5-1.5Z"></path>
                            <path fill="currentColor" d="M17.5 5h-1a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5Z"></path>
                            </svg>
                        </a>
                        <!-- Reports -->
                        <a href="#" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" x-tooltip.placement.right="'Reports'">
                            <svg class="size-7" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.85714 3H4.14286C3.51167 3 3 3.51167 3 4.14286V9.85714C3 10.4883 3.51167 11 4.14286 11H9.85714C10.4883 11 11 10.4883 11 9.85714V4.14286C11 3.51167 10.4883 3 9.85714 3Z" fill="currentColor"></path>
                            <path d="M9.85714 12.8999H4.14286C3.51167 12.8999 3 13.4116 3 14.0428V19.757C3 20.3882 3.51167 20.8999 4.14286 20.8999H9.85714C10.4883 20.8999 11 20.3882 11 19.757V14.0428C11 13.4116 10.4883 12.8999 9.85714 12.8999Z" fill="currentColor" fill-opacity="0.3"></path>
                            <path d="M19.757 3H14.0428C13.4116 3 12.8999 3.51167 12.8999 4.14286V9.85714C12.8999 10.4883 13.4116 11 14.0428 11H19.757C20.3882 11 20.8999 10.4883 20.8999 9.85714V4.14286C20.8999 3.51167 20.3882 3 19.757 3Z" fill="currentColor" fill-opacity="0.3"></path>
                            <path d="M19.757 12.8999H14.0428C13.4116 12.8999 12.8999 13.4116 12.8999 14.0428V19.757C12.8999 20.3882 13.4116 20.8999 14.0428 20.8999H19.757C20.3882 20.8999 20.8999 20.3882 20.8999 19.757V14.0428C20.8999 13.4116 20.3882 12.8999 19.757 12.8999Z" fill="currentColor" fill-opacity="0.3"></path>
                            </svg>
                        </a>
                        <!-- New Log with Extra-Large Modal -->
                        <div x-data="{ showModal: false }">
                            <!-- Button -->
                            <a 
                                href="javascript:void(0)" 
                                class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25"
                                x-tooltip.placement.right="'New Log'"
                                @click="showModal = true"
                            >
                                <svg class="size-7" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.3111 14.75H5.03356C3.36523 14.75 2.30189 12.9625 3.10856 11.4958L5.24439 7.60911L7.24273 3.96995C8.07689 2.45745 10.2586 2.45745 11.0927 3.96995L13.1002 7.60911L14.0627 9.35995L15.2361 11.4958C16.0427 12.9625 14.9794 14.75 13.3111 14.75Z" fill="currentColor"></path>
                                <path fill-opacity="0.3" d="M21.1667 15.2083C21.1667 18.4992 18.4992 21.1667 15.2083 21.1667C11.9175 21.1667 9.25 18.4992 9.25 15.2083C9.25 15.0525 9.25917 14.9058 9.26833 14.75H13.3108C14.9792 14.75 16.0425 12.9625 15.2358 11.4958L14.0625 9.36C14.4292 9.28666 14.8142 9.25 15.2083 9.25C18.4992 9.25 21.1667 11.9175 21.1667 15.2083Z" fill="currentColor"></path>
                                </svg>
                            </a>
                            <!-- Add Modal -->
                            <template x-teleport="#x-teleport-target">
                                <div 
                                    class="fixed inset-0 z-[100] flex items-center justify-center px-4 overflow-hidden py-6 sm:px-5"
                                    x-show="showModal"
                                    @keydown.window.escape="showModal = false"
                                >
                                    <!-- Overlay -->
                                    <div 
                                    style="border; 1px solid crimson;"
                                        class="absolute inset-0 bg-gray-900 bg-opacity-50 transition-opacity"
                                        x-show="showModal"
                                        x-transition:enter="ease-out duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="ease-in duration-200"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        @click="showModal = false"
                                    >
                                    </div>

                                    <!-- Modal Content -->
                                    <div 
                                        class="relative w-full max-w-5xl p-8 bg-white rounded-lg shadow-lg dark:bg-gray-800 p-6"
                                        x-show="showModal"
                                        x-transition:enter="ease-out duration-300"  
                                        x-transition:enter-start="opacity-0 translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 translate-y-4"
                                    >
                                        <!-- Header -->
                                        <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                                            <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200">Call Log Form</h2>
                                            <button 
                                                @click="showModal = false" 
                                                class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none"
                                            >
                                                ✖
                                            </button>
                                        </div>

                                        <!-- Modal Form -->
                                        <form class="grid grid-cols-2 gap-8 mt-6" method="post" action="process_add_log.php">
                                            <!-- Left Column -->
                                            <div class="space-y-1">
                                                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                                                <div>
                                                    <label for="service_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Type</label>
                                                    <select id="service_type" name="service_type" class="w-full mt-1 rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                                                        <option>Select Service Type</option>
                                                        <!-- PHP ... -->
                                                        <?php
                                                            // Fetch service types from the database
                                                            $service_types_result = mysqli_query($conn, "SELECT * FROM service_types");
                                                            while ($row = mysqli_fetch_assoc($service_types_result)) {
                                                                echo "<option value='{$row['id']}'>{$row['service_type']}</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Call Type -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="call_type">
                                                        Call Type
                                                    </label>
                                                    <select id="call_type" name="call_type"
                                                        class="w-full mt-1 rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                    >
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
                                            </div>
                                            <!-- Right Column -->
                                            <div class="space-y-1">
                                                <!-- Name -->
                                                <div>
                                                    <label for="name"  class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Name
                                                    </label>
                                                    <input id="name" name="name"
                                                        type="text" 
                                                        class="w-full mt-1 rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                        placeholder="Enter Name"
                                                    />
                                                </div>

                                                <!-- Location -->
                                                <div>
                                                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Location
                                                    </label>
                                                    <input id="location" name="location"
                                                        type="text" 
                                                        class="w-full mt-1 rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                        placeholder="Enter Location"
                                                    />
                                                </div>

                                            </div>
                                            <!-- Full-Width Section -->
                                            <div class="col-span-2 space-y-6">
                                                <!-- CN & T -->
                                                <div class="grid grid-cols-2 gap-4">
                                                        <!-- Contact Number -->
                                                        <div>
                                                            <label for="contactNumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                                Contact Number
                                                            </label>
                                                            <input id="contactNumber" name="contactNumber"
                                                                type="text" 
                                                                class="w-full mt-1 rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                                placeholder="Enter Contact Number"
                                                            />
                                                        </div>
                                                        <!-- Time -->
                                                        <div>
                                                            <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                                Time
                                                            </label>
                                                            <input id="time" name="call_time"
                                                                type="text" 
                                                                class="w-full mt-1 rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                                placeholder="Enter Time"
                                                            />
                                                        </div>
                                                </div>
                                                <!-- AGE and STATUS -->
                                                <div class="grid grid-cols-2 gap-4">
                                                    <!-- Age -->
                                                    <div>
                                                        <label for="age" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Age
                                                        </label>
                                                        <input id="age" name="age"
                                                            type="number" 
                                                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                            placeholder="Enter Age"
                                                        />
                                                    </div>
                                                    <!-- Status  -->
                                                    <div >
                                                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Status
                                                        </label>
                                                        <select id="status" name="status"
                                                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900"
                                                        >
                                                            <option>Select Status</option>
                                                            <option value="pending_case">pending_case</option>
                                                            <option value="closed_case">closed_case</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- Reason of Call -->
                                                <div>
                                                    <label for="reason_of_call" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Reason of Call
                                                    </label>
                                                    <textarea id="reason_of_call" name="reason_of_call"
                                                        rows="3" 
                                                        class="w-full mt-1 rounded-md form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                        placeholder="Enter Reason of Call"
                                                    ></textarea>
                                                </div>
                                                <!-- Actions Taken -->
                                                <div>
                                                    <label for="action_taken" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Actions Taken
                                                    </label>
                                                    <textarea id="action_taken" name="action_taken"
                                                        rows="3" 
                                                        class="w-full mt-1 rounded-md form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                        placeholder="Enter Actions Taken"
                                                    ></textarea>
                                                </div>
                                                <!-- Remarks -->
                                                <div>
                                                    <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
                                                    <textarea id="remarks" name="remarks" rows="4" class="w-full rounded-md form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"></textarea>
                                                </div>
                                            </div>
                                            <!-- Submit Button -->
                                            <div class="col-span-2 text-right">
                                                <button
                                                    type="submit"
                                                    class="btn bg-gradient-to-r from-sky-400 to-blue-600 font-medium text-white"
                                                >
                                                    Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </template>

                        </div>
                    </div>

                    <!-- Bottom Links -->
                    <div class="flex flex-col items-center space-y-3 py-3">
                        <!-- Settings -->
                        <a href="#" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                            <svg class="size-7" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-opacity="0.3" fill="currentColor" d="M2 12.947v-1.771c0-1.047.85-1.913 1.899-1.913 1.81 0 2.549-1.288 1.64-2.868a1.919 1.919 0 0 1 .699-2.607l1.729-.996c.79-.474 1.81-.192 2.279.603l.11.192c.9 1.58 2.379 1.58 3.288 0l.11-.192c.47-.795 1.49-1.077 2.279-.603l1.73.996a1.92 1.92 0 0 1 .699 2.607c-.91 1.58-.17 2.868 1.639 2.868 1.04 0 1.899.856 1.899 1.912v1.772c0 1.047-.85 1.912-1.9 1.912-1.808 0-2.548 1.288-1.638 2.869.52.915.21 2.083-.7 2.606l-1.729.997c-.79.473-1.81.191-2.279-.604l-.11-.191c-.9-1.58-2.379-1.58-3.288 0l-.11.19c-.47.796-1.49 1.078-2.279.605l-1.73-.997a1.919 1.919 0 0 1-.699-2.606c.91-1.58.17-2.869-1.639-2.869A1.911 1.911 0 0 1 2 12.947Z"></path>
                            <path fill="currentColor" d="M11.995 15.332c1.794 0 3.248-1.464 3.248-3.27 0-1.807-1.454-3.272-3.248-3.272-1.794 0-3.248 1.465-3.248 3.271 0 1.807 1.454 3.271 3.248 3.271Z"></path>
                            </svg>
                        </a>

                        <!-- Profile -->
                        <div x-data="usePopper({placement:'right-end',offset:12})" @click.outside="isShowPopper && (isShowPopper = false)" class="flex">
                            <button @click="isShowPopper = !isShowPopper" x-ref="popperRef" class="avatar size-12">
                                <img class="rounded-full" src="assets/images/911 Official Logo.webp" alt="avatar">
                                <span class="absolute right-0 size-3.5 rounded-full border-2 border-white bg-success dark:border-navy-700"></span>
                            </button>

                            <div :class="isShowPopper && 'show'" class="popper-root fixed" x-ref="popperRoot">
                                <div class="popper-box w-64 rounded-lg border border-slate-150 bg-white shadow-soft dark:border-navy-600 dark:bg-navy-700">
                                    <div class="flex items-center space-x-4 rounded-t-lg bg-slate-100 py-5 px-4 dark:bg-navy-800">
                                        <div class="avatar size-14">
                                            <img class="rounded-full" src="assets/images/911 Official Logo.webp" alt="avatar">
                                        </div>
                                        <div>
                                            <a href="#" class="text-base font-medium text-slate-700 hover:text-primary focus:text-primary dark:text-navy-100 dark:hover:text-accent-light dark:focus:text-accent-light">
                                            <?= $_SESSION['full_name'] ?>
                                            </a>
                                            <p class="text-xs text-slate-400 dark:text-navy-300">
                                                Emergency Telecommunicator
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col pt-2 pb-5">
                                        <a href="#" class="group flex items-center space-x-3 py-2 px-4 tracking-wide outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600">
                                            <div class="flex size-8 items-center justify-center rounded-lg bg-warning text-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h2 class="font-medium text-slate-700 transition-colors group-hover:text-primary group-focus:text-primary dark:text-navy-100 dark:group-hover:text-accent-light dark:group-focus:text-accent-light">
                                                    Profile
                                                </h2>
                                                <div class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                                                    Your profile setting
                                                </div>
                                            </div>
                                        </a>
                                        <a href="logout.php">                        
                                            <div class="mt-3 px-4">
                                                <button class="btn h-9 w-full space-x-2 bg-primary text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                    </svg>
                                                    <span>Logout</span>
                                                </button>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Panel -->
            <div class="sidebar-panel">
            <div class="flex h-full grow flex-col bg-white pl-[var(--main-sidebar-width)] dark:bg-navy-750">
                <!-- Sidebar Panel Header -->
                <!-- Sidebar Panel Body -->
            </div>
            </div>
        </div>

        <!-- App Header Wrapper-->
        <nav class="header before:bg-white dark:before:bg-navy-750 print:hidden">
            <!-- App Header  -->
            <div class="header-container relative flex w-full bg-white dark:bg-navy-750 print:hidden">
                <!-- Header Items -->
                <div class="flex w-full items-center justify-between">
                    <!-- Left: Sidebar Toggle Button -->
                    <div class="size-7">
                    </div>

                    <!-- Right: Header buttons -->
                    <div class="-mr-1.5 flex items-center space-x-2">
                        <!-- Mobile Search Toggle -->
                        <button @click="$store.global.isSearchbarActive = !$store.global.isSearchbarActive" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25 sm:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5.5 text-slate-500 dark:text-navy-100" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>

                        <!-- Main Searchbar -->
                        <template x-if="$store.breakpoints.smAndUp">
                            <div class="flex" x-data="usePopper({placement:'bottom-end',offset:12})" @click.outside="isShowPopper && (isShowPopper = false)">
                                <div class="relative mr-4 flex h-8">
                                    <input placeholder="Search here..." class="form-input peer h-full rounded-full bg-slate-150 px-4 pl-9 text-xs+ text-slate-800 ring-primary/50 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:text-navy-100 dark:placeholder-navy-300 dark:ring-accent/50 dark:hover:bg-navy-900 dark:focus:bg-navy-900" :class="isShowPopper ? 'w-80' : 'w-60'" @focus="isShowPopper= true" type="text" x-ref="popperRef">
                                    <div class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 transition-colors duration-200" fill="currentColor" viewbox="0 0 24 24">
                                            <path d="M3.316 13.781l.73-.171-.73.171zm0-5.457l.73.171-.73-.171zm15.473 0l.73-.171-.73.171zm0 5.457l.73.171-.73-.171zm-5.008 5.008l-.171-.73.171.73zm-5.457 0l-.171.73.171-.73zm0-15.473l-.171-.73.171.73zm5.457 0l.171-.73-.171.73zM20.47 21.53a.75.75 0 101.06-1.06l-1.06 1.06zM4.046 13.61a11.198 11.198 0 010-5.115l-1.46-.342a12.698 12.698 0 000 5.8l1.46-.343zm14.013-5.115a11.196 11.196 0 010 5.115l1.46.342a12.698 12.698 0 000-5.8l-1.46.343zm-4.45 9.564a11.196 11.196 0 01-5.114 0l-.342 1.46c1.907.448 3.892.448 5.8 0l-.343-1.46zM8.496 4.046a11.198 11.198 0 015.115 0l.342-1.46a12.698 12.698 0 00-5.8 0l.343 1.46zm0 14.013a5.97 5.97 0 01-4.45-4.45l-1.46.343a7.47 7.47 0 005.568 5.568l.342-1.46zm5.457 1.46a7.47 7.47 0 005.568-5.567l-1.46-.342a5.97 5.97 0 01-4.45 4.45l.342 1.46zM13.61 4.046a5.97 5.97 0 014.45 4.45l1.46-.343a7.47 7.47 0 00-5.568-5.567l-.342 1.46zm-5.457-1.46a7.47 7.47 0 00-5.567 5.567l1.46.342a5.97 5.97 0 014.45-4.45l-.343-1.46zm8.652 15.28l3.665 3.664 1.06-1.06-3.665-3.665-1.06 1.06z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Dark Mode Toggle -->
                        <button @click="$store.global.isDarkModeEnabled = !$store.global.isDarkModeEnabled" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                            <svg x-show="$store.global.isDarkModeEnabled" x-transition:enter="transition-transform duration-200 ease-out absolute origin-top" x-transition:enter-start="scale-75" x-transition:enter-end="scale-100 static" class="size-6 text-amber-400" fill="currentColor" viewbox="0 0 24 24">
                                <path d="M11.75 3.412a.818.818 0 01-.07.917 6.332 6.332 0 00-1.4 3.971c0 3.564 2.98 6.494 6.706 6.494a6.86 6.86 0 002.856-.617.818.818 0 011.1 1.047C19.593 18.614 16.218 21 12.283 21 7.18 21 3 16.973 3 11.956c0-4.563 3.46-8.31 7.925-8.948a.818.818 0 01.826.404z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" x-show="!$store.global.isDarkModeEnabled" x-transition:enter="transition-transform duration-200 ease-out absolute origin-top" x-transition:enter-start="scale-75" x-transition:enter-end="scale-100 static" class="size-6 text-amber-400" viewbox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <!-- Monochrome Mode Toggle -->
                        <button @click="$store.global.isMonochromeModeEnabled = !$store.global.isMonochromeModeEnabled" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                            <i class="fa-solid fa-palette bg-gradient-to-r from-sky-400 to-blue-600 bg-clip-text text-lg font-semibold text-transparent"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <main class="main-content w-full px-[var(--margin-x)] pb-8">
            <div class="mt-4 grid grid-cols-12 gap-4 sm:mt-5 sm:gap-5 lg:mt-6 lg:gap-6">
                <!-- First Begginingning -->
                <div class="col-span-12 lg:col-span-8">
                    <div class="flex items-center justify-between space-x-2">
                        <h2 class="text-base font-medium tracking-wide text-slate-800 line-clamp-1 dark:text-navy-100">
                            Overview
                        </h2>
                        <div x-data="{activeTab:'tabRecent'}" class="is-scrollbar-hidden overflow-x-auto rounded-lg bg-slate-200 text-slate-600 dark:bg-navy-800 dark:text-navy-200">
                            <div class="tabs-list flex p-1">
                                <button @click="activeTab = 'tabRecent'" :class="activeTab === 'tabRecent' ? 'bg-white shadow dark:bg-navy-500 dark:text-navy-100' : 'hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'" class="btn shrink-0 px-3 py-1 text-xs+ font-medium">
                                    This month
                                </button>
                                <button @click="activeTab = 'tabAll'" :class="activeTab === 'tabAll' ? 'bg-white shadow dark:bg-navy-500 dark:text-navy-100' : 'hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'" class="btn shrink-0 px-3 py-1 text-xs+ font-medium">
                                    This year
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Chart -->
                    <div class="flex flex-col sm:flex-row sm:space-x-7">
                        <div class="ax-transparent-gridline grid w-full grid-cols-1">
                            <div x-init="$nextTick(() => { $el._x_chart = new ApexCharts($el,pages.charts.analyticsSalesOverview); $el._x_chart.render() });"></div>
                        </div>
                    </div>
                </div>
                <!-- The 6Cards -->
                <div class="col-span-12 lg:col-span-4">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-5 lg:grid-cols-2">
                        <!-- Police -->
                        <div class="rounded-lg bg-slate-150 p-4 dark:bg-navy-700">
                                <div class="flex justify-between space-x-1">
                                    <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">
                                        67.6k
                                    </p>
                                    <svg version="1.1" id="Icons" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" style="enable-background:new 0 0 32 32;" xml:space="preserve" class="size-5 text-primary" x="0px" y="0px" viewBox="0 0 32 32"  xml:space="preserve">
                                        <style type="text/css">
                                            .st0{fill:none;stroke:#000000;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
                                            .st1{fill:none;stroke:#000000;stroke-width:2;stroke-linejoin:round;stroke-miterlimit:10;}
                                        </style>
                                        <path class="st0" d="M24.1,13H7.9c-1.2,0-2.3-0.6-3-1.6L4,10l12-6l12,6l-0.9,1.4C26.4,12.4,25.3,13,24.1,13z" id="id_101" style="stroke: rgb(11, 57, 224);"></path>
                                        <path class="st0" d="M8.8,17.8c-0.6-0.7-1.2-1-1.8-0.8c-0.9,0.4-1.2,2-0.7,3.6c0.5,1.6,1.7,2.7,2.6,2.3c0,0,0.1,0,0.1-0.1
                                            c0.8,3.5,3.6,6.1,7,6.1s6.3-2.6,7-6.1c0,0,0.1,0.1,0.1,0.1c0.9,0.4,2.1-0.7,2.6-2.3C26.3,19,26,17.4,25,17c-0.6-0.2-1.3,0.1-1.8,0.8
                                            " id="id_102" style="stroke: rgb(11, 65, 224);"></path>
                                        <path class="st0" d="M21.8,18H10.2C8.5,18,7,16.5,7,14.8V13h18v1.8C25,16.5,23.5,18,21.8,18z" id="id_103" style="stroke: rgb(11, 47, 224);"></path>
                                    </svg>
                                </div>
                                <p class="mt-1 text-xs+">Police</p>
                        </div>
                        <!-- Fire -->
                        <div class="rounded-lg bg-slate-150 p-4 dark:bg-navy-700">
                                <div class="flex justify-between">
                                    <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">
                                        12.6K
                                    </p>
                                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25" class="size-5 text-primary" x="0px" y="0px" viewBox="0 0 32 32"  xml:space="preserve"><path id="Fire" d="M17.35,3a12.08,12.08,0,0,0-4.72-3,.5.5,0,0,0-.52.16.5.5,0,0,0-.06.54,9,9,0,0,1,.83,6.4.16.16,0,0,1-.15.12c-.09,0-.11,0-.11-.07a10.31,10.31,0,0,0-3.7-4.57.5.5,0,0,0-.76.54c.59,2.31-.57,3.7-2,5.46S3,12.4,3,15.72a9.47,9.47,0,0,0,7.38,9.08A3.26,3.26,0,0,1,9,22.17c0-5.34,3.5-6.67,3.5-6.67C13.2,19,16,19.8,16,22.17a3.26,3.26,0,0,1-1.35,2.61,9.5,9.5,0,0,0,4-1.93A9.26,9.26,0,0,0,22,15.69C22,9,19.47,5.1,17.35,3Z" fill="#d45324"></path></svg>
                                </div>
                                <p class="mt-1 text-xs+">Fire</p>
                        </div>
                        <!-- EMS -->
                        <div class="rounded-lg bg-slate-150 p-4 dark:bg-navy-700">
                                <div class="flex justify-between">
                                    <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">
                                        143
                                    </p>
                                    <svg version="1.1" id="Icons" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" style="enable-background:new 0 0 32 32;" xml:space="preserve" class="size-5 text-primary" x="0px" y="0px" viewBox="0 0 32 32"  xml:space="preserve">
                                        <style type="text/css">
                                            .st0{fill:none;stroke:#000000;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;}
                                        </style>
                                        <path class="st0" d="M27,7L27,7c-2.6-2.7-6.9-2.7-9.5,0l-1.3,1.4c-0.1,0.1-0.4,0.1-0.5,0L14.4,7C11.8,4.3,7.6,4.3,5,7l0,0
                                            c-2.6,2.7-2.6,7.1,0,9.8l1.6,1.6l9.2,9.5c0.1,0.1,0.4,0.1,0.5,0l9.2-9.5l1.6-1.6C29.7,14.1,29.7,9.7,27,7z" id="id_101" style="stroke: rgb(0, 143, 5);"></path>
                                        <polyline class="st0" points="9,15 12,15 14,13 16,17 18,12 20,15 23,15 " id="id_102" style="stroke: rgb(0, 143, 10);"></polyline>
                                    </svg>
                                </div>
                                <p class="mt-1 text-xs+">EMS</p>
                        </div>
                        <!-- Rescue -->
                        <div class="rounded-lg bg-slate-150 p-4 dark:bg-navy-700">
                                <div class="flex justify-between">
                                    <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">
                                        651
                                    </p>
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 271.2159 167.2434" enable-background="new 0 0 271.2159 167.2434" xml:space="preserve" class="size-5 text-primary" x="0px" y="0px" viewBox="0 0 32 32"  xml:space="preserve">
                                        <g>
                                            <path d="M101.2848,99.4422l-11.2356,2.0806l7.7838-9.0459l-0.2503-0.5051c-1.9751-3.9844-5.918-6.6201-10.355-6.9209
                                                c-4.4321-0.2891-8.6997,1.7783-11.1938,5.4609l-12.3873,18.2838c1.9348,0.4188,4.0506,0.64,6.3555,0.64
                                                c6.0273,0,13.1611-1.4307,21.2041-4.251c1.0918-0.3818,2.2261-0.5752,3.373-0.5752c4.3267,0,8.1958,2.7432,9.6274,6.8262
                                                c1.8604,5.3066-0.9434,11.1387-6.25,13.001c-3.653,1.2807-7.1945,2.3259-10.6238,3.1548l15.8499,31.9819
                                                c2.1816,4.4023,6.6084,6.9521,11.21,6.9521c1.8643,0,3.7568-0.4189,5.541-1.3027c6.1855-3.0654,8.7149-10.5654,5.6494-16.751
                                                L101.2848,99.4422z" id="id_101" style="fill: rgb(0, 78, 214);"></path>
                                            <path d="M46.525,123.5939c-15.3633-9.7256-17.3447-26.9854-17.4219-27.7148c-0.5884-5.5938,3.4829-10.623,9.0762-11.2119
                                                c0.3628-0.0391,0.7256-0.0576,1.085-0.0576c5.1846,0,9.5347,3.8916,10.1182,9.0537c0.0354,0.2753,0.9136,6.634,5.9749,11.1194
                                                c0.1759-2.065,0.3464-4.0493,0.4923-5.9194c1.7022-10.2193-3.3994-21.6182-12.1221-22.7471
                                                c-8.7255-1.1308-20.4834,4.9014-23.7978,20.0264c-4.4336,20.8242-4.376,34.0361,0.7529,55.8037
                                                c4.9649,15.6338,16.1162,16.0038,25.2061,14.831c1.2133-0.1562,2.3319-0.3184,3.359-0.5131
                                                c3.6483-0.295,7.1351-2.1752,9.3481-5.4427l21.5411-31.7946c-3.5527,0.548-6.9594,0.8317-10.1959,0.8317
                                                C60.9713,129.8585,53.0929,127.7501,46.525,123.5939z" id="id_102" style="fill: rgb(0, 89, 214);"></path>
                                            <path d="M38.9528,69.3361c8.4688-9.0898,7.9639-23.3242-1.125-31.791c-9.0898-8.4697-23.3261-7.963-31.7929,1.126
                                                c-8.4668,9.0889-7.9639,23.3252,1.1259,31.79C16.2517,78.9309,30.4851,78.426,38.9528,69.3361z" id="id_103" style="fill: rgb(0, 78, 214);"></path>
                                            <path d="M102.1305,112.1632c-1.4614-4.168-6.0264-6.3613-10.1963-4.9023c-15.5503,5.4531-27.835,5.8145-35.5249,1.0479
                                                c-7.8926-4.8916-9.1484-13.8936-9.2129-14.3984c-0.4932-4.3613-4.4165-7.5215-8.7866-7.0557
                                                c-4.394,0.4629-7.5811,4.3994-7.1187,8.7939c0.0723,0.6865,1.937,16.9238,16.4106,26.0859
                                                c6.2393,3.9492,13.6714,5.9238,22.2393,5.9238c8.1001,0,17.2153-1.7666,27.2871-5.2979
                                                C101.3976,120.8976,103.5924,116.3331,102.1305,112.1632z" id="id_104" style="fill: rgb(0, 89, 214);"></path>
                                            <path d="M215.1632,68.4787c2.7295-3.46,2.1387-8.4648-1.3105-11.207c-3.4512-2.7422-8.4785-2.166-11.2324,1.2725
                                                c-0.2002,0.251-20.457,25.0488-57.4473,28.3438c-4.4004,0.3926-7.6504,4.2773-7.2588,8.6787c0.3711,4.1592,3.8613,7.291,7.96,7.291
                                                c0.2373,0,0.4775-0.0107,0.7188-0.0322C190.6857,98.8976,214.1837,69.7189,215.1632,68.4787z" id="id_105" style="fill: rgb(0, 78, 214);"></path>
                                            <path d="M167.5567,27.3564c10.0096-4.0692,17.2694-9.8916,17.3782-13.6705c1.6923,3.4852-4.8347,11.0107-14.8322,17.0082
                                                c-1.9102,1.1459-3.8106,2.1542-5.6511,3.014c5.5116,9.626,17.4211,13.9339,27.9809,9.6411
                                                c11.511-4.6796,17.0503-17.8042,12.37-29.317c-4.6796-11.5109-17.806-17.0496-29.317-12.37
                                                c-5.7141,2.3229-9.9534,6.7273-12.2045,11.981c-0.374,0.144-0.7461,0.2814-1.1224,0.4344
                                                c-10.9999,4.4718-18.7095,11.07-17.2185,14.7375c0.4669,1.1485,1.7825,1.8725,3.7061,2.1885
                                                C152.8623,31.6965,160.0031,30.4271,167.5567,27.3564z" id="id_106" style="fill: rgb(0, 71, 214);"></path>
                                            <path d="M258.7159,139.6896h-28.8223l11.9691-11.9691c0.3816-0.3499,0.7521-0.7156,1.1052-1.1052l0.5869-0.5869
                                                c2.3561-2.3561,3.5637-5.419,3.6455-8.506c0.35-2.0624,0.4602-4.3897,0.2696-7.0308
                                                c-3.3011-22.1187-8.1426-34.4116-19.9762-52.1111c-8.6824-12.8206-21.8384-14.0671-29.524-9.7839
                                                c-5.3934,3.0046-7.2419,10.0476-6.0161,17.0443c5.8098-4.6754,8.8754-8.3801,8.9517-8.4747
                                                c1.9473-2.4316,4.8574-3.8281,7.9805-3.8281c2.291,0,4.541,0.7852,6.335,2.2109c4.3867,3.4873,5.1357,9.8984,1.6699,14.292
                                                c-0.4714,0.5965-6.0241,7.4921-16.4325,15.0594c2.242,4.5643,4.5507,9.4693,6.6194,14.5439l-16.1053-8.3823
                                                c-6.0145,3.4756-13.0497,6.7711-21.091,9.3294l-3.9106,14.252h14.733v9.6665h8.5392l1.4321-5.2189l16.0884,8.3735l-15.6427,15.6427
                                                v18.1481c2.2775,2.1583,5.3301,3.4343,8.5953,3.4343h59c6.9033,0,12.5-5.5967,12.5-12.5S265.6193,139.6896,258.7159,139.6896z" id="id_107" style="fill: rgb(0, 64, 214);"></path>
                                            <path d="M188.5207,126.91H178.104v-9.6666h-31v9.6666h-10.4165v40.3334h51.8333V126.91z M151.604,121.7434h22v5.1666h-22V121.7434z
                                                M162.6044,159.576c-7.1567,0-12.9583-5.8016-12.9583-12.9584c0-7.1567,5.8016-12.9583,12.9583-12.9583
                                                c7.1567,0,12.9584,5.8016,12.9584,12.9583C175.5628,153.7744,169.7611,159.576,162.6044,159.576z" id="id_108" style="fill: rgb(0, 54, 214);"></path>
                                            <polygon points="164.1994,139.8394 161.0095,139.8394 161.0095,145.0228 155.8262,145.0228 155.8262,148.2125 161.0095,148.2125 
                                                161.0095,153.3959 164.1994,153.3959 164.1994,148.2125 169.3826,148.2125 169.3826,145.0228 164.1994,145.0228 	" id="id_109" style="fill: rgb(0, 36, 214);"></polygon>
                                            <polygon points="116.9582,53.6102 109.1525,68.7455 115.6514,69.4152 107.3073,82.7296 107.9312,83.085 119.6468,68.2805 
                                                112.5004,67.0355 119.7519,55.0401 	" id="id_110" style="fill: rgb(0, 29, 214);"></polygon>
                                            <polygon points="118.4625,77.4173 122.9531,80.6409 111.2324,86.9079 111.5408,87.4293 126.3649,81.4868 121.6383,77.5683 
                                                132.0045,71.8101 130.5557,69.586 	" id="id_111" style="fill: rgb(0, 21, 214);"></polygon>
                                            <polygon points="101.0587,82.5246 104.5558,67.1915 98.7364,68.8184 99.8832,57.2008 97.268,57.1273 96.8342,71.3052 
                                                101.9612,69.4783 100.4663,82.4797 	" id="id_112" style="fill: rgb(0, 11, 214);"></polygon>
                                            <path d="M70.8101,17.6214c-1.3214-7.6642-15.8112-4.1926-19.4353,4.015C63.7395,19.8244,72.0537,24.8342,70.8101,17.6214z" id="id_113" style="fill: rgb(0, 46, 214);"></path>
                                            <path d="M49.6422,5.8136c-5.1253-3.4202-11.032,6.8005-8.2914,13.3592C47.0523,11.0784,54.4656,9.0322,49.6422,5.8136z" id="id_114" style="fill: rgb(0, 54, 214);"></path>
                                            <path d="M63.8547,34.4126c0.0838-5.3875-10.2266-4.8833-13.7472,0.2396C58.7583,35.0051,63.7759,39.4827,63.8547,34.4126z" id="id_115" style="fill: rgb(0, 46, 214);"></path>
                                        </g>
                                    </svg>
                                </div>
                                <p class="mt-1 text-xs+">Rescue</p>
                        </div>
                        <!-- Prank Call -->
                        <div class="rounded-lg bg-slate-150 p-4 dark:bg-navy-700">
                                <div class="flex justify-between space-x-1">
                                    <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">
                                        46k
                                    </p>
                                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128" class="size-5 text-primary" x="0px" y="0px" viewBox="0 0 32 32"  xml:space="preserve"><defs><style>.cls-1{fill:#2d3e50;}.cls-2{fill:#1d75b8;}</style></defs><path class="cls-1" d="M82.87289,83.9591a7.82414,7.82414,0,0,0-8.31.60211l-5.9872,4.68343a2.81182,2.81182,0,0,1-2.86731.35916Q63.72666,88.73,61.73281,87.679A57.39135,57.39135,0,0,1,46.83248,76.91515,74.08485,74.08485,0,0,1,34.254,60.20019q-1.04644-1.99081-1.9248-3.97554a2.81184,2.81184,0,0,1,.35918-2.86729l4.68343-5.9872a7.8242,7.8242,0,0,0,.60211-8.31006C37.7677,38.67246,27.144,24.48971,26.876,24.20514a7.82078,7.82078,0,0,0-11.95573.763s-7.32853,9.01939-7.33265,9.02352c-13.50226,16.08807,2.74665,35.84248,13.791,47.8312,1.497,1.62489,5.36958,5.55928,9.27094,9.46065s7.83578,7.77393,9.46067,9.271c11.98874,11.0443,31.74313,27.29323,47.83535,13.791,0-.00413,9.01939-7.33265,9.01939-7.33265a7.81376,7.81376,0,0,0,.76294-11.9516C97.4433,94.78894,83.26055,84.1653,82.87289,83.9591Z" id="id_101" style="fill: rgb(2, 90, 158);"></path><path class="cls-2" d="M103.111,9.31258A29.12266,29.12266,0,0,0,69.07073,27.80742c-.42416,1.18556-10.32332,26.55007-13.58661,34.9089a1.4544,1.4544,0,0,0,1.71823,1.937l20.06772-5.16712A29.08257,29.08257,0,1,0,103.111,9.31258Z" id="id_102" style="fill: rgb(2, 98, 158);"></path></svg>
                                </div>
                                <p class="mt-1 text-xs+">Prank Calls</p>
                        </div>
                        <!-- Other Call -->
                        <div class="rounded-lg bg-slate-150 p-4 dark:bg-navy-700">
                                <div class="flex justify-between">
                                    <p class="text-xl font-semibold text-slate-700 dark:text-navy-100">
                                        8.8k
                                    </p>
                                    <svg id="Glyph" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" class="size-5 text-primary" x="0px" y="0px" viewBox="0 0 32 32"  xml:space="preserve"><path d="M52.03607,41.70952a5.28684,5.28684,0,0,0-7.8388.59016,4.753,4.753,0,0,1-5.91078.66364,55.71391,55.71391,0,0,1-9.18743-7.28048,55.21156,55.21156,0,0,1-8.04959-9.97764,4.69777,4.69777,0,0,1,.67433-5.87915,5.5731,5.5731,0,0,0,2.08606-4.21447,5.09326,5.09326,0,0,0-1.50659-3.62438l-5.247-5.24695a5.13549,5.13549,0,0,0-7.24883,0L7.67918,8.86856A12.51666,12.51666,0,0,0,6.05665,24.59893,119.55907,119.55907,0,0,0,21.14433,43.016,120.21638,120.21638,0,0,0,39.2558,57.85081a12.62,12.62,0,0,0,15.899-1.5172c1.51909-1.53991,3.79025-3.33474,3.62427-5.75278a5.09282,5.09282,0,0,0-1.50651-3.62436Z" id="id_101" style="fill: rgb(0, 88, 176);"></path><path d="M51.72444,12.29221a28.06388,28.06388,0,0,0-19.97586-8.275,1.05367,1.05367,0,0,0,0,2.10722c14.05278-.29118,26.43686,12.09609,26.14394,26.148A1.05385,1.05385,0,0,0,60,32.27213,28.06615,28.06615,0,0,0,51.72444,12.29221Z" id="id_102" style="fill: rgb(0, 79, 176);"></path><path d="M30.69291,22.3802a1.05366,1.05366,0,0,0,1.05361,1.05361,8.87964,8.87964,0,0,1,8.83676,8.8369,1.05365,1.05365,0,0,0,2.10729-.00007,10.94544,10.94544,0,0,0-10.944-10.944A1.05366,1.05366,0,0,0,30.69291,22.3802Z" id="id_103" style="fill: rgb(0, 73, 176);"></path><path d="M49.23807,32.27167a1.05373,1.05373,0,0,0,2.10721-.00005c.21748-10.53544-9.064-19.81737-19.59994-19.59958a1.05363,1.05363,0,0,0,.0002,2.107A17.57455,17.57455,0,0,1,49.23807,32.27167Z" id="id_104" style="fill: rgb(0, 79, 176);"></path></svg>
                                </div>
                                <p class="mt-1 text-xs+">Other Calls</p>
                        </div>
                    </div>
                </div>
                <!-- Table Contents -->
                <div class="col-span-12">                  
                    <div x-data="{activeTab:'tabPending'}" class="tabs flex flex-col">
                        <div class="is-scrollbar-hidden overflow-x-auto">
                            <div class="border-b-2 border-slate-150 dark:border-navy-500">
                                <div class="tabs-list flex">
                                    <button
                                        @click="activeTab = 'tabPending'"
                                        :class="activeTab === 'tabPending' ? 'border-primary dark:border-accent text-primary dark:text-accent-light' : 'border-transparent hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'"
                                        class="btn shrink-0 rounded-none border-b-2 px-3 py-2 font-medium"
                                    >
                                        911 Emergency Call Log
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content pt-4">
                            <div
                                x-show="activeTab === 'tabPending'"
                                x-transition:enter="transition-all duration-500 easy-in-out"
                                x-transition:enter-start="opacity-0 [transform:translate3d(0,1rem,0)]"
                                x-transition:enter-end="opacity-100 [transform:translate3d(0,0,0)]"
                            >
                                <div>
                                    <!-- Emergency Call Log -->
                                    <div class="col-span-12">
                                        <div class="flex items-center justify-between">
                                            <h2 class="text-base font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100">
                                            </h2>
                                            <div class="flex">
                                                <div class="flex items-center" x-data="{isInputActive:false}">
                                                    <label class="block">
                                                        <input x-effect="isInputActive === true && $nextTick(() => { $el.focus()});" :class="isInputActive ? 'w-32 lg:w-48' : 'w-0'" class="form-input bg-transparent px-1 text-right transition-all duration-100 placeholder:text-slate-500 dark:placeholder:text-navy-200" id="searchID" placeholder="Search here..." type="text">
                                                    </label>
                                                    <button @click="isInputActive = !isInputActive" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Table  -->
                                    <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
                                  <!-- Table for displaying cases -->
                                  <table id="searchTable" class="is-zebra w-full text-left" id="callLogTable">
    <thead>
        <tr>
            <th class="whitespace-nowrap rounded-l-lg bg-slate-200 px-3 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">#</th>
            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Action</th>
            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Type of Service</th>
            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Call Type</th>
            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Call Date</th>
            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Call Time</th>
            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Contact Number</th>
            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Call Count</th>
            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Name</th>
            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Age</th>
            <th class="whitespace-nowrap rounded-r-lg bg-slate-200 px-3 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Location</th>
            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
            // Query for both Pending and Closed Cases filtered by team and user_id
            $query = "
                SELECT cl.agent_id, cl.team, st.service_type, ct.call_type, cl.call_date, cl.call_time,
                        cl.contact_number, 
                        COUNT(*) OVER (PARTITION BY cl.contact_number) AS contact_count, 
                        cl.name, cl.age, cl.location, cl.status
                        FROM $table_name cl
                        LEFT JOIN service_types st ON cl.type_of_service = st.id
                        LEFT JOIN call_types ct ON cl.call_type = ct.id
                        WHERE cl.agent_id = '$user_id' AND cl.team = '$team' AND (cl.status = 'pending_case' OR cl.status = 'closed_case')
                        ORDER BY CASE WHEN cl.status = 'pending_case' THEN 1 ELSE 2 END, cl.call_date DESC";

            $result = mysqli_query($conn, $query);
            if (mysqli_num_rows($result) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    // Apply color and opacity based on status: warning for pending, primary for closed
                    $rowClass = '';
                    if ($row['status'] == 'pending_case') {
                        $rowClass = 'bg-warning';  // Yellow for pending cases
                    } elseif ($row['status'] == 'closed_case') {
                        $rowClass = 'bg-success';  // Green for closed cases
                    }

                    echo "<tr class='hover:bg-slate-100 clickable-row $rowClass' 
                            data-agent-id='{$row['agent_id']}' 
                            data-service-type='{$row['service_type']}' 
                            data-call-type='{$row['call_type']}' 
                            data-call-date='{$row['call_date']}' 
                            data-call-time='{$row['call_time']}' 
                            data-contact-number='{$row['contact_number']}' 
                            data-call-count='{$row['contact_count']}' 
                            data-name='{$row['name']}' 
                            data-age='{$row['age']}' 
                            data-location='{$row['location']}'>";

                   
                    echo "<td class='whitespace-nowrap rounded-l-lg px-4 py-3 sm:px-5'>{$no}</td>
                          <td class='whitespace-nowrap px-4 py-3 sm:px-5'>
                              <button 
                                class='btn rounded-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90' 
                                onclick='openModal(\"view\", 
                                \"{$row['agent_id']}\", 
                                \"{$row['service_type']}\", 
                                \"{$row['call_type']}\", 
                                \"{$row['call_date']}\", 
                                \"{$row['call_time']}\", 
                                \"{$row['contact_number']}\", 
                                \"{$row['contact_count']}\", 
                                \"{$row['name']}\", 
                                \"{$row['age']}\", 
                                \"{$row['location']}\")'>
                                View
                              </button>
                              <button 
                                class='btn rounded-full bg-success font-medium text-white hover:bg-success-focus focus:bg-success-focus active:bg-success-focus/90' 
                                onclick='openModal(\"edit\", 
                                \"{$row['agent_id']}\", 
                                \"{$row['service_type']}\", 
                                \"{$row['call_type']}\", 
                                \"{$row['call_date']}\", 
                                \"{$row['call_time']}\", 
                                \"{$row['contact_number']}\", 
                                \"{$row['contact_count']}\", 
                                \"{$row['name']}\", 
                                \"{$row['age']}\", 
                                \"{$row['location']}\")'>
                                Edit
                              </button>
                              <button 
                                class='btn rounded-full bg-error font-medium text-white hover:bg-error-focus focus:bg-error-focus active:bg-error-focus/90' 
                                onclick='openModal(\"delete\", \"{$row['agent_id']}\")'>
                                Delete
                              </button>
                          </td>
                          <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['service_type']}</td>
                          <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['call_type']}</td>
                          <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['call_date']}</td>
                          <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['call_time']}</td>
                          <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['contact_number']}</td>
                          <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['contact_count']}</td>
                          <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['name']}</td>
                          <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['age']}</td>
                          <td class='whitespace-nowrap rounded-r-lg px-4 py-3 sm:px-5'>{$row['location']}</td>
                          <td class='whitespace-nowrap rounded-r-lg px-4 py-3 sm:px-5'>{$row['status']}</td>
                    </tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='12' class='text-center'>No cases found</td></tr>";
            }
        ?>
    </tbody>
</table>


<!-- Modal (for view/edit) -->
<div id="modal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <div id="modal-body">
            <!-- Dynamic content will be loaded here (view/edit details) -->
        </div>
    </div>
</div>




                                    </div>

                                   
                                    <!-- Pagination -->
                                    <div class="flex flex-col justify-between space-y-4 px-4 py-4 sm:flex-row sm:items-center sm:space-y-0 sm:px-5">
                                        <div class="flex items-center space-x-2 text-xs+">
                                            <span>Show</span>
                                                <label class="block">
                                                    <select id="entriesPerPage" class="form-select rounded-full border border-slate-300 bg-white px-2 py-1 pr-6 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                                    <option>10</option>
                                                    <option>30</option>
                                                    <option>50</option>
                                                    </select>
                                                </label>
                                            <span>entries</span>
                                        </div>
                                            <ol id="paginationControls" class="pagination">
                                            </ol>
                                        <div id="tableInfo" class="text-xs+"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
        <!-- 
            This is a place for Alpine.js Teleport feature 
            @see https://alpinejs.dev/directives/teleport
        -->
        <div id="x-teleport-target"></div>
            <script>
                window.addEventListener("DOMContentLoaded", () => Alpine.start());
                
                // Pagination
                document.addEventListener('DOMContentLoaded', function () {
                    const table = document.querySelector('#callLogTable tbody');
                    const entriesPerPageSelect = document.querySelector('#entriesPerPage');
                    const paginationControls = document.querySelector('#paginationControls');
                    const tableInfo = document.querySelector('#tableInfo');

                    let rows = Array.from(table.querySelectorAll('tr'));
                    let currentPage = 1;
                    let rowsPerPage = parseInt(entriesPerPageSelect.value);

                    function renderTable() {
                        const start = (currentPage - 1) * rowsPerPage;
                        const end = start + rowsPerPage;

                        // Hide all rows
                        rows.forEach((row, index) => {
                            row.style.display = index >= start && index < end ? '' : 'none';
                        });

                        // Update table info
                        const totalRows = rows.length;
                        tableInfo.textContent = `${start + 1} - ${Math.min(end, totalRows)} of ${totalRows} entries`;

                        renderPaginationControls(totalRows);
                    }

                    function renderPaginationControls(totalRows) {
                        paginationControls.innerHTML = '';
                        const totalPages = Math.ceil(totalRows / rowsPerPage);

                        for (let i = 1; i <= totalPages; i++) {
                            const li = document.createElement('li');
                            li.classList.add('bg-slate-150', 'dark:bg-navy-500');
                            if (i === currentPage) {
                                li.innerHTML = `<a href="#" class="flex h-8 min-w-[2rem] items-center justify-center rounded-lg bg-primary px-3 leading-tight text-white">${i}</a>`;
                            } else {
                                li.innerHTML = `<a href="#" class="flex h-8 min-w-[2rem] items-center justify-center rounded-lg px-3 leading-tight">${i}</a>`;
                            }

                            li.querySelector('a').addEventListener('click', (e) => {
                                e.preventDefault();
                                currentPage = i;
                                renderTable();
                            });

                            paginationControls.appendChild(li);
                        }
                    }

                    entriesPerPageSelect.addEventListener('change', () => {
                        rowsPerPage = parseInt(entriesPerPageSelect.value);
                        currentPage = 1; // Reset to the first page
                        renderTable();
                    });

                    // Initialize table rendering
                    renderTable();
                });

            </script>

<script>
    // Get the input fields and tables for Searching Data
    const searchPendingInput = document.getElementById('searchID');
    const pendingTable = document.getElementById('searchTable');
    const pendingRows = pendingTable.getElementsByTagName('tr');  // Get all rows from the pending call log table


    // Listen for input in the search field for pending calls
    searchPendingInput.addEventListener('input', function() {
        const searchTerm = searchPendingInput.value.toLowerCase();  // Convert input to lowercase for case-insensitive comparison

        // Loop through all rows
        for (let i = 1; i < pendingRows.length; i++) {  // Start from 1 to skip the header row
            const cells = pendingRows[i].getElementsByTagName('td');
            let rowContainsSearchTerm = false;

            // Loop through all cells in the row
            for (let j = 0; j < cells.length; j++) {
                const cellText = cells[j].textContent || cells[j].innerText;  // Get text content of the cell
                if (cellText.toLowerCase().includes(searchTerm)) {  // Check if search term is found
                    rowContainsSearchTerm = true;
                    break;  // If found, no need to check further cells in this row
                }
            }

            // Show/hide row based on whether it contains the search term
            if (rowContainsSearchTerm) {
                pendingRows[i].style.display = '';  // Show row
            } else {
                pendingRows[i].style.display = 'none';  // Hide row
            }
        }
    });


</script>


<!-- JavaScript -->
<script>
    // Function to open modal
    function openModal(action, id, serviceType = '', callType = '', callDate = '', callTime = '', contactNumber = '', contactCount = '', name = '', age = '', location = '') {
        var modal = document.getElementById("modal");
        modal.style.display = "flex";  // Show the modal
        
        var modalBody = document.getElementById("modal-body");

        // Load content based on action (view/edit)
        if (action === 'view') {
    // Show case details for viewing
    modalBody.innerHTML = `
        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">View Case</h3>
        <div class="grid grid-cols-2 gap-8 mt-6 relative w-full max-w-5xl p-8 bg-white rounded-lg shadow-lg dark:bg-gray-800 p-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Type</label>
                <input type="text" name="service_type" value="${serviceType}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Type</label>
                <input type="text" name="call_type" value="${callType}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Date</label>
                <input type="text" name="call_date" value="${callDate}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Time</label>
                <input type="text" name="call_time" value="${callTime}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Count</label>
                <input type="text" name="contact_count" value="${contactCount}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" value="${name}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Age</label>
                <input type="text" name="age" value="${age}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                <input type="text" name="location" value="${location}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent" readonly>
            </div>
            <div class="flex justify-end space-x-4">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Close</button>
            </div>
        </div>
    `;

        } else if (action === 'edit') {
            // Show an edit form with current values for editing
            modalBody.innerHTML = `
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Case</h3>
                <form method="POST" action="update.php" class="space-y-4">
                    <input type="hidden" name="agent_id" value="${id}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Type</label>
                        <input type="text" name="service_type" value="${serviceType}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Type</label>
                        <input type="text" name="call_type" value="${callType}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Date</label>
                        <input type="text" name="call_date" value="${callDate}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Time</label>
                        <input type="text" name="call_time" value="${callTime}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                        <input type="text" name="contact_number" value="${contactNumber}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Count</label>
                        <input type="number" name="call_count" value="${contactCount}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="name" value="${name}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Age</label>
                        <input type="text" name="age" value="${age}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                        <input type="text" name="location" value="${location}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent">
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Changes</button>
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">Cancel</button>
                    </div>
                </form>
            `;
        } else if (action === 'delete') {
            // Delete confirmation modal
            modalBody.innerHTML = `
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Delete Case</h3>
                <p class="text-lg text-gray-700 dark:text-gray-300">Are you sure you want to delete this case?</p>
                <div class="flex justify-end space-x-4 mt-4">
                    <button onclick="deleteCase(${id})" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Yes</button>
                    <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">No</button>
                </div>
            `;
        }
    }

    // Function to close modal
    function closeModal() {
        var modal = document.getElementById("modal");
        modal.style.display = "none"; // Hide the modal
    }

    // Function to delete the case (triggered from modal)
    function deleteCase(id) {
        if (confirm('Are you sure you want to delete this case?')) {
            window.location.href = 'delete.php?id=' + id; // Redirect to delete page
        }
    }
</script>


</body>
</html>
