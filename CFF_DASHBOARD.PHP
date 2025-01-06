<?php
session_start();
include('includes/db.php'); // Include the database connection
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit();
}
include('ditokalagayphp.php');
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
<link rel="stylesheet" href="assets/css/modal.css">

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
                        <a href="user_dashboard2.php" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" x-tooltip.placement.right="'Dashboard'">
                            <svg class="size-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24">
                            <path fill="currentColor" fill-opacity=".3" d="M5 14.059c0-1.01 0-1.514.222-1.945.221-.43.632-.724 1.453-1.31l4.163-2.974c.56-.4.842-.601 1.162-.601.32 0 .601.2 1.162.601l4.163 2.974c.821.586 1.232.88 1.453 1.31.222.43.222.935.222 1.945V19c0 .943 0 1.414-.293 1.707C18.414 21 17.943 21 17 21H7c-.943 0-1.414 0-1.707-.293C5 20.414 5 19.943 5 19v-4.94Z"></path>
                            <path fill="currentColor" d="M3 12.387c0 .267 0 .4.084.441.084.041.19-.04.4-.204l7.288-5.669c.59-.459.885-.688 1.228-.688.343 0 .638.23 1.228.688l7.288 5.669c.21.163.316.245.4.204.084-.04.084-.174.084-.441v-.409c0-.48 0-.72-.102-.928-.101-.208-.291-.355-.67-.65l-7-5.445c-.59-.459-.885-.688-1.228-.688-.343 0-.638.23-1.228.688l-7 5.445c-.379.295-.569.442-.67.65-.102.208-.102.448-.102.928v.409Z"></path>
                            <path fill="currentColor" d="M11.5 15.5h1A1.5 1.5 0 0 1 14 17v3.5h-4V17a1.5 1.5 0 0 1 1.5-1.5Z"></path>
                            <path fill="currentColor" d="M17.5 5h-1a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5Z"></path>
                            </svg>
                        </a>
                        <!-- Reports -->
                        <a href="user_dashboard3.php" class="flex size-11 items-center justify-center rounded-lg bg-primary/10 text-primary outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:bg-navy-600 dark:text-accent-light dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90" x-tooltip.placement.right="'Reports'">
                            <svg class="size-7" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.85714 3H4.14286C3.51167 3 3 3.51167 3 4.14286V9.85714C3 10.4883 3.51167 11 4.14286 11H9.85714C10.4883 11 11 10.4883 11 9.85714V4.14286C11 3.51167 10.4883 3 9.85714 3Z" fill="currentColor"></path>
                            <path d="M9.85714 12.8999H4.14286C3.51167 12.8999 3 13.4116 3 14.0428V19.757C3 20.3882 3.51167 20.8999 4.14286 20.8999H9.85714C10.4883 20.8999 11 20.3882 11 19.757V14.0428C11 13.4116 10.4883 12.8999 9.85714 12.8999Z" fill="currentColor" fill-opacity="0.3"></path>
                            <path d="M19.757 3H14.0428C13.4116 3 12.8999 3.51167 12.8999 4.14286V9.85714C12.8999 10.4883 13.4116 11 14.0428 11H19.757C20.3882 11 20.8999 10.4883 20.8999 9.85714V4.14286C20.8999 3.51167 20.3882 3 19.757 3Z" fill="currentColor" fill-opacity="0.3"></path>
                            <path d="M19.757 12.8999H14.0428C13.4116 12.8999 12.8999 13.4116 12.8999 14.0428V19.757C12.8999 20.3882 13.4116 20.8999 14.0428 20.8999H19.757C20.3882 20.8999 20.8999 20.3882 20.8999 19.757V14.0428C20.8999 13.4116 20.3882 12.8999 19.757 12.8999Z" fill="currentColor" fill-opacity="0.3"></path>
                            </svg>
                        </a>
                        <!-- Directory -->
                        <a href="directory.php" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" x-tooltip.placement.right="'Directory'">
                            <svg class="size-7" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-opacity="0.25" d="M21.0001 16.05V18.75C21.0001 20.1 20.1001 21 18.7501 21H6.6001C6.9691 21 7.3471 20.946 7.6981 20.829C7.7971 20.793 7.89609 20.757 7.99509 20.712C8.31009 20.586 8.61611 20.406 8.88611 20.172C8.96711 20.109 9.05711 20.028 9.13811 19.947L9.17409 19.911L15.2941 13.8H18.7501C20.1001 13.8 21.0001 14.7 21.0001 16.05Z" fill="currentColor"></path>
                            <path fill-opacity="0.5" d="M17.7324 11.361L15.2934 13.8L9.17334 19.9111C9.80333 19.2631 10.1993 18.372 10.1993 17.4V8.70601L12.6384 6.26701C13.5924 5.31301 14.8704 5.31301 15.8244 6.26701L17.7324 8.17501C18.6864 9.12901 18.6864 10.407 17.7324 11.361Z" fill="currentColor"></path>
                            <path d="M7.95 3H5.25C3.9 3 3 3.9 3 5.25V17.4C3 17.643 3.02699 17.886 3.07199 18.12C3.09899 18.237 3.12599 18.354 3.16199 18.471C3.20699 18.606 3.252 18.741 3.306 18.867C3.315 18.876 3.31501 18.885 3.31501 18.885C3.32401 18.885 3.32401 18.885 3.31501 18.894C3.44101 19.146 3.585 19.389 3.756 19.614C3.855 19.731 3.95401 19.839 4.05301 19.947C4.15201 20.055 4.26 20.145 4.377 20.235L4.38601 20.244C4.61101 20.415 4.854 20.559 5.106 20.685C5.115 20.676 5.11501 20.676 5.11501 20.685C5.25001 20.748 5.385 20.793 5.529 20.838C5.646 20.874 5.76301 20.901 5.88001 20.928C6.11401 20.973 6.357 21 6.6 21C6.969 21 7.347 20.946 7.698 20.829C7.797 20.793 7.89599 20.757 7.99499 20.712C8.30999 20.586 8.61601 20.406 8.88601 20.172C8.96701 20.109 9.05701 20.028 9.13801 19.947L9.17399 19.911C9.80399 19.263 10.2 18.372 10.2 17.4V5.25C10.2 3.9 9.3 3 7.95 3ZM6.6 18.75C5.853 18.75 5.25 18.147 5.25 17.4C5.25 16.653 5.853 16.05 6.6 16.05C7.347 16.05 7.95 16.653 7.95 17.4C7.95 18.147 7.347 18.75 6.6 18.75Z" fill="currentColor"></path>
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
                                            <div class="col-span-2 space-y-6">
                                                <!-- ST CT L-->
                                                <div class="grid grid-cols-3 gap-6">
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
                                                <!-- CN CD CT -->
                                                <div class="grid grid-cols-3 gap-6">
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
                                                    <!-- Call Date -->
                                                    <div>
                                                        <label for="callDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            Call Date
                                                        </label>
                                                        <input id="callDate" name="callDate"
                                                            type="text"
                                                            class="w-full mt-1 rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500 mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                           placeholder="Call Date"
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
                                                <div class="grid grid-cols-3 gap-6 md:grid-cols-2">
                                                    <!-- N A S-->
                                                     <!-- Name -->
                                                     <div class="col-span-2">
                                                        <label for="name"  class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Name
                                                        </label>
                                                        <input id="name" name="name"
                                                            type="text" 
                                                            class="w-full mt-1 rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                            placeholder="Enter Name"
                                                        />
                                                    </div>
                                                    <div class="flex items-center gap-6">
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
                                                                <option value="pending_case">Pending Case</option>
                                                                <option value="closed_case">Case Closed</option>
                                                            </select>
                                                        </div>
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
                                                    <textarea id="remarks" name="remarks" rows="4" class="w-full rounded-md form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" 
                                                    placeholder="Enter Reamrks"></textarea>
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
                <div class="col-span-12 lg:col-span-8 xl:col-span-9">
                    <!-- Top Saver -->
                    <div :class="$store.breakpoints.smAndUp && 'via-purple-300'" class="card mt-12 bg-gradient-to-l from-pink-300 to-indigo-400 p-5 sm:mt-0 sm:flex-row">
                        <div class="mt-2 flex-1 pt-2 text-center text-white sm:mt-0 sm:text-left">
                            <h1 class="text-xl">
                            Welcome to your <span class="font-semibold">Report Log </span>
                            </h1>
                        </div>
                    </div>
                    <!-- Contacts -->
                    <div class="mt-4 sm:mt-5 lg:mt-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-base font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100">
                            <span>Your Directory</span>
                                </h2>
                            <div class="flex">
                                <div class="flex items-center" x-data="{isInputActive:false}">
                                    <label class="block">
                                        <input x-effect="isInputActive === true && $nextTick(() => { $el.focus()});" :class="isInputActive ? 'w-32 lg:w-48' : 'w-0'" class="form-input bg-transparent px-1 text-right transition-all duration-100 placeholder:text-slate-500 dark:placeholder:text-navy-200" placeholder="Search here..." type="text">
                                    </label>
                                    <button @click="isInputActive = !isInputActive" class="btn size-8 rounded-full p-0 hover:bg-slate-300/20 focus:bg-slate-300/20 active:bg-slate-300/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewbox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
                                <table class="is-hoverable w-full text-left">
                                    <thead>
                                        <tr>
                                            <th class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                                NO
                                            </th>
                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                            CFF STATUS
                                            </th>
                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                            ACTIONS
                                            </th>
                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                            NAME
                                            </th>
                                            <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                            CALL TYPE
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                    </tbody>
                                </table>
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

                // Dito po modal nyu ha!
                // Function to open modal
                function openModal(action, id, serviceType = '', callType = '', callDate = '', callTime = '', contactNumber = '', contactCount = '', name = '', age = '', location = '', reasonOfCall = '', actionsTaken = '', remarks = '', status='') {
                    var modal = document.getElementById("modal");
                    modal.style.display = "flex";  // Show the modal
                    
                    var modalBody = document.getElementById("modal-body");

                    // Load content based on action (view/edit)
                    if (action === 'view') {
                // Show case details for viewing
                modalBody.innerHTML = `
                    <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-200">View Case</h3>
                    <form class="grid grid-cols-2 gap-8 mt-6">
                    <div class="col-span-2 space-y-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Type</label>
                                <input type="text" name="service_type" value="${serviceType}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Type</label>
                                <input type="text" name="call_type" value="${callType}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                <input type="text" name="location" value="${location}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                                <input type="text" name="contact_number" value="${contactNumber}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Date</label>
                                <input type="text" name="call_date" value="${callDate}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Time</label>
                                <input type="text" name="call_time" value="${callTime}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" name="name" value="${name}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                            </div>
                            <div class="flex items-center gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Age</label>
                                    <input type="text" name="age" value="${age}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                    <input type="text" name="status" value="${status}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Reason of Call
                            </label>
                            <textarea id="reason_of_call" name="reason_of_call"
                                rows="3" value="${reasonOfCall}"
                                class="form-textarea w-full resize-none rounded-lg bg-slate-150 p-2.5 placeholder:text-slate-400 dark:bg-navy-900 dark:placeholder:text-navy-300"
                                readonly
                                ></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                 Actions Taken
                            </label>
                            <textarea id="action_taken" name="action_taken"
                                rows="3" value="${actionsTaken}"
                                class="form-textarea w-full resize-none rounded-lg bg-slate-150 p-2.5 placeholder:text-slate-400 dark:bg-navy-900 dark:placeholder:text-navy-300"
                                readonly
                            ></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
                            <textarea id="remarks" name="remarks" value="${remarks}" rows="4" class="form-textarea w-full resize-none rounded-lg bg-slate-150 p-2.5 placeholder:text-slate-400 dark:bg-navy-900 dark:placeholder:text-navy-300" readonly></textarea>
                        </div>
                    </div>
                    </form>
                `;

                    } else if (action === 'edit') {
                        // Show an edit form with current values for editing
                        modalBody.innerHTML = `
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Edit Case</h3>
                            <form method="POST" action="update.php" class="grid grid-cols-2 gap-8 mt-6">
                                <div class="col-span-2 space-y-6">
                                    <div class="grid grid-cols-3 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service Type</label>
                                            <input type="text" name="service_type" value="${serviceType}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Type</label>
                                            <input type="text" name="call_type" value="${callType}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                                            <input type="text" name="location" value="${location}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                                            <input type="text" name="contact_number" value="${contactNumber}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Date</label>
                                            <input type="text" name="call_date" value="${callDate}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Call Time</label>
                                            <input type="text" name="call_time" value="${callTime}" class="w-full rounded-lg bg-slate-150 px-3 py-2 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" readonly disabled>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-6 md:grid-cols-2">
                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                            <input type="text" name="name" value="${name}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent">
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Age</label>
                                                <input type="text" name="age" value="${age}" class="w-full mt-1 rounded-lg border border-gray-300 p-2 bg-transparent">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                                <select name="status" value="${status}"
                                                    class="mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                                                >
                                                    <option value="pending_case">Pending Case</option>
                                                    <option value="closed_case">Case Closed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Reason of Call
                                        </label>
                                        <textarea id="reason_of_call" name="reason_of_call"
                                            rows="3" value="${reasonOfCall}"
                                            class="w-full mt-1 rounded-md form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                            ></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Actions Taken
                                        </label>
                                        <textarea id="action_taken" name="action_taken"
                                            rows="3" value="${actionsTaken}"
                                            class="w-full mt-1 rounded-md form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                        ></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Remarks</label>
                                        <textarea id="remarks" name="remarks" value="${remarks}" rows="4" class="w-full mt-1 rounded-md form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"></textarea>
                                    </div>
                                <div class="flex justify-end space-x-4">
                                    <button type="submit" class="btn bg-success font-medium text-white hover:bg-success-focus hover:shadow-lg hover:shadow-success/50 focus:bg-success-focus focus:shadow-lg focus:shadow-success/50 active:bg-success-focus/90">Save Changes</button>
                                    <button type="button" onclick="closeModal()" class="btn bg-info font-medium text-white hover:bg-info-focus hover:shadow-lg hover:shadow-info/50 focus:bg-info-focus focus:shadow-lg focus:shadow-info/50 active:bg-info-focus/90">Cancel</button>
                                </div>
                            </form>
                        `;
                    } else if (action === 'delete') {
                        // Delete confirmation modal
                        modalBody.innerHTML = `
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Delete Case</h3>
                            <p class="text-lg text-gray-700 dark:text-gray-300">Are you sure you want to delete this case? Really? Sure kana ba?</p>
                            <div class="flex justify-end space-x-4 mt-4">
                                <button onclick="deleteCase(${id})" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Yes</button>
                                <button onclick="closeModal()" class="btn bg-gradient-to-r from-fuchsia-600 to-pink-600 font-medium text-white">No</button>
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

                // Sa Date na dae nag wowork punyeta!
                document.addEventListener("DOMContentLoaded", () => {
                    console.log("Script is running");

                    // Select the input field
                    const callDateInput = document.getElementById('callDate');
                    console.log("Input field:", callDateInput);

                    if (callDateInput) {
                        // Get the current date
                        const currentDate = new Date();

                        // Format the date as mm-dd-yyyy
                        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
                        const day = String(currentDate.getDate()).padStart(2, '0');
                        const year = currentDate.getFullYear();

                        const formattedDate = `${month}-${day}-${year}`;
                        console.log("Formatted Date:", formattedDate);

                        // Display the formatted date in the input field
                        callDateInput.value = formattedDate;
                    } else {
                        console.error("Input field not found.");
                    }
                });


            </script>
</body>
</html>
