<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

  // Array of service types to count
$types = ['Police', 'Fire', 'EMS', 'Rescue', 'Other'];
$counts = [];

// Loop through each service type and get the count
foreach ($types as $type) {
    // SQL query to count the number of calls for each service type
    $query = "
        SELECT COUNT(*) AS count 
        FROM call_logs cl
        JOIN service_types st ON cl.type_of_service = st.id
        WHERE st.service_type = '$type'
    ";
    $result = mysqli_query($conn, $query);

    // Fetch the result and store the count
    $row = mysqli_fetch_assoc($result);
    $counts[$type] = $row['count'];
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Meta tags  -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <title>911 Emergency System</title>
    <link rel="icon" type="image/webp" href="../assets/images/911 Official Logo.webp">

    <!-- CSS Assets -->
    <link rel="stylesheet" href="./assets/css/app.css">
    <link rel="stylesheet" href="./assets/css/app3.css">

    <!-- Javascript Assets -->
    <script src="./assets/js/app.js" defer=""></script>


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
              <a href="admin_dashboard2.php">
                <img class="size-11 transition-transform duration-500 ease-in-out hover:rotate-[360deg]" src="assets/images/911 Official Logo.webp" alt="logo">
              </a>
            </div>

            <!-- Main Sections Links -->
            <div class="is-scrollbar-hidden flex grow flex-col space-y-4 overflow-y-auto pt-6">
              <!-- Dashobards -->
              <a href="admin_dashboard2.php" class="flex size-11 items-center justify-center rounded-lg bg-primary/10 text-primary outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:bg-navy-600 dark:text-accent-light dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90" x-tooltip.placement.right="'Dashboards'">
                <svg class="size-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24">
                  <path fill="currentColor" fill-opacity=".3" d="M5 14.059c0-1.01 0-1.514.222-1.945.221-.43.632-.724 1.453-1.31l4.163-2.974c.56-.4.842-.601 1.162-.601.32 0 .601.2 1.162.601l4.163 2.974c.821.586 1.232.88 1.453 1.31.222.43.222.935.222 1.945V19c0 .943 0 1.414-.293 1.707C18.414 21 17.943 21 17 21H7c-.943 0-1.414 0-1.707-.293C5 20.414 5 19.943 5 19v-4.94Z"></path>
                  <path fill="currentColor" d="M3 12.387c0 .267 0 .4.084.441.084.041.19-.04.4-.204l7.288-5.669c.59-.459.885-.688 1.228-.688.343 0 .638.23 1.228.688l7.288 5.669c.21.163.316.245.4.204.084-.04.084-.174.084-.441v-.409c0-.48 0-.72-.102-.928-.101-.208-.291-.355-.67-.65l-7-5.445c-.59-.459-.885-.688-1.228-.688-.343 0-.638.23-1.228.688l-7 5.445c-.379.295-.569.442-.67.65-.102.208-.102.448-.102.928v.409Z"></path>
                  <path fill="currentColor" d="M11.5 15.5h1A1.5 1.5 0 0 1 14 17v3.5h-4V17a1.5 1.5 0 0 1 1.5-1.5Z"></path>
                  <path fill="currentColor" d="M17.5 5h-1a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5Z"></path>
                </svg>
              </a>

              <!-- Apps -->
              <!-- <a href="apps-list.html" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" x-tooltip.placement.right="'Applications'">
                <svg class="size-7" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5 8H19V16C19 17.8856 19 18.8284 18.4142 19.4142C17.8284 20 16.8856 20 15 20H9C7.11438 20 6.17157 20 5.58579 19.4142C5 18.8284 5 17.8856 5 16V8Z" fill="currentColor" fill-opacity="0.3"></path>
                  <path d="M12 8L11.7608 5.84709C11.6123 4.51089 10.4672 3.5 9.12282 3.5V3.5C7.68381 3.5 6.5 4.66655 6.5 6.10555V6.10555C6.5 6.97673 6.93539 7.79026 7.66025 8.2735L9.5 9.5" stroke="currentColor" stroke-linecap="round"></path>
                  <path d="M12 8L12.2392 5.84709C12.3877 4.51089 13.5328 3.5 14.8772 3.5V3.5C16.3162 3.5 17.5 4.66655 17.5 6.10555V6.10555C17.5 6.97673 17.0646 7.79026 16.3397 8.2735L14.5 9.5" stroke="currentColor" stroke-linecap="round"></path>
                  <rect x="4" y="8" width="16" height="3" rx="1" fill="currentColor"></rect>
                  <path d="M12 11V15" stroke="currentColor" stroke-linecap="round"></path>
                </svg>
              </a> -->

              <!-- Pages And Layouts -->
              <a href="pages-card-user-1.html" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" x-tooltip.placement.right="'Reports'">
                <svg class="size-7" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M9.85714 3H4.14286C3.51167 3 3 3.51167 3 4.14286V9.85714C3 10.4883 3.51167 11 4.14286 11H9.85714C10.4883 11 11 10.4883 11 9.85714V4.14286C11 3.51167 10.4883 3 9.85714 3Z" fill="currentColor"></path>
                  <path d="M9.85714 12.8999H4.14286C3.51167 12.8999 3 13.4116 3 14.0428V19.757C3 20.3882 3.51167 20.8999 4.14286 20.8999H9.85714C10.4883 20.8999 11 20.3882 11 19.757V14.0428C11 13.4116 10.4883 12.8999 9.85714 12.8999Z" fill="currentColor" fill-opacity="0.3"></path>
                  <path d="M19.757 3H14.0428C13.4116 3 12.8999 3.51167 12.8999 4.14286V9.85714C12.8999 10.4883 13.4116 11 14.0428 11H19.757C20.3882 11 20.8999 10.4883 20.8999 9.85714V4.14286C20.8999 3.51167 20.3882 3 19.757 3Z" fill="currentColor" fill-opacity="0.3"></path>
                  <path d="M19.757 12.8999H14.0428C13.4116 12.8999 12.8999 13.4116 12.8999 14.0428V19.757C12.8999 20.3882 13.4116 20.8999 14.0428 20.8999H19.757C20.3882 20.8999 20.8999 20.3882 20.8999 19.757V14.0428C20.8999 13.4116 20.3882 12.8999 19.757 12.8999Z" fill="currentColor" fill-opacity="0.3"></path>
                </svg>
              </a>

              <!-- Forms -->
              <a href="form-input-text.html" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" x-tooltip.placement.right="'Settings'">
                <svg class="size-7" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-opacity="0.25" d="M21.0001 16.05V18.75C21.0001 20.1 20.1001 21 18.7501 21H6.6001C6.9691 21 7.3471 20.946 7.6981 20.829C7.7971 20.793 7.89609 20.757 7.99509 20.712C8.31009 20.586 8.61611 20.406 8.88611 20.172C8.96711 20.109 9.05711 20.028 9.13811 19.947L9.17409 19.911L15.2941 13.8H18.7501C20.1001 13.8 21.0001 14.7 21.0001 16.05Z" fill="currentColor"></path>
                  <path fill-opacity="0.5" d="M17.7324 11.361L15.2934 13.8L9.17334 19.9111C9.80333 19.2631 10.1993 18.372 10.1993 17.4V8.70601L12.6384 6.26701C13.5924 5.31301 14.8704 5.31301 15.8244 6.26701L17.7324 8.17501C18.6864 9.12901 18.6864 10.407 17.7324 11.361Z" fill="currentColor"></path>
                  <path d="M7.95 3H5.25C3.9 3 3 3.9 3 5.25V17.4C3 17.643 3.02699 17.886 3.07199 18.12C3.09899 18.237 3.12599 18.354 3.16199 18.471C3.20699 18.606 3.252 18.741 3.306 18.867C3.315 18.876 3.31501 18.885 3.31501 18.885C3.32401 18.885 3.32401 18.885 3.31501 18.894C3.44101 19.146 3.585 19.389 3.756 19.614C3.855 19.731 3.95401 19.839 4.05301 19.947C4.15201 20.055 4.26 20.145 4.377 20.235L4.38601 20.244C4.61101 20.415 4.854 20.559 5.106 20.685C5.115 20.676 5.11501 20.676 5.11501 20.685C5.25001 20.748 5.385 20.793 5.529 20.838C5.646 20.874 5.76301 20.901 5.88001 20.928C6.11401 20.973 6.357 21 6.6 21C6.969 21 7.347 20.946 7.698 20.829C7.797 20.793 7.89599 20.757 7.99499 20.712C8.30999 20.586 8.61601 20.406 8.88601 20.172C8.96701 20.109 9.05701 20.028 9.13801 19.947L9.17399 19.911C9.80399 19.263 10.2 18.372 10.2 17.4V5.25C10.2 3.9 9.3 3 7.95 3ZM6.6 18.75C5.853 18.75 5.25 18.147 5.25 17.4C5.25 16.653 5.853 16.05 6.6 16.05C7.347 16.05 7.95 16.653 7.95 17.4C7.95 18.147 7.347 18.75 6.6 18.75Z" fill="currentColor"></path>
                </svg>
              </a>

              <!-- Components -->
              <!-- <a href="components-accordion.html" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" x-tooltip.placement.right="'Components'">
                <svg class="size-7" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-opacity="0.5" d="M14.2498 16C14.2498 17.5487 13.576 18.9487 12.4998 19.9025C11.5723 20.7425 10.3473 21.25 8.99976 21.25C6.10351 21.25 3.74976 18.8962 3.74976 16C3.74976 13.585 5.39476 11.5375 7.61726 10.9337C8.22101 12.4562 9.51601 13.6287 11.1173 14.0662C11.5548 14.1887 12.0185 14.25 12.4998 14.25C12.981 14.25 13.4448 14.1887 13.8823 14.0662C14.1185 14.6612 14.2498 15.3175 14.2498 16Z" fill="currentColor"></path>
                  <path d="M17.75 9.00012C17.75 9.68262 17.6187 10.3389 17.3825 10.9339C16.7787 12.4564 15.4837 13.6289 13.8825 14.0664C13.445 14.1889 12.9813 14.2501 12.5 14.2501C12.0187 14.2501 11.555 14.1889 11.1175 14.0664C9.51625 13.6289 8.22125 12.4564 7.6175 10.9339C7.38125 10.3389 7.25 9.68262 7.25 9.00012C7.25 6.10387 9.60375 3.75012 12.5 3.75012C15.3962 3.75012 17.75 6.10387 17.75 9.00012Z" fill="currentColor"></path>
                  <path fill-opacity="0.3" d="M21.25 16C21.25 18.8962 18.8962 21.25 16 21.25C14.6525 21.25 13.4275 20.7425 12.5 19.9025C13.5763 18.9487 14.25 17.5487 14.25 16C14.25 15.3175 14.1187 14.6612 13.8825 14.0662C15.4837 13.6287 16.7787 12.4562 17.3825 10.9337C19.605 11.5375 21.25 13.585 21.25 16Z" fill="currentColor"></path>
                </svg>
              </a> -->

              <!-- Elements -->
              <!-- <a href="elements-avatar.html" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25" x-tooltip.placement.right="'Elements'">
                <svg class="size-7" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M13.3111 14.75H5.03356C3.36523 14.75 2.30189 12.9625 3.10856 11.4958L5.24439 7.60911L7.24273 3.96995C8.07689 2.45745 10.2586 2.45745 11.0927 3.96995L13.1002 7.60911L14.0627 9.35995L15.2361 11.4958C16.0427 12.9625 14.9794 14.75 13.3111 14.75Z" fill="currentColor"></path>
                  <path fill-opacity="0.3" d="M21.1667 15.2083C21.1667 18.4992 18.4992 21.1667 15.2083 21.1667C11.9175 21.1667 9.25 18.4992 9.25 15.2083C9.25 15.0525 9.25917 14.9058 9.26833 14.75H13.3108C14.9792 14.75 16.0425 12.9625 15.2358 11.4958L14.0625 9.36C14.4292 9.28666 14.8142 9.25 15.2083 9.25C18.4992 9.25 21.1667 11.9175 21.1667 15.2083Z" fill="currentColor"></path>
                </svg>
              </a>  -->
            </div>

            <!-- Bottom Links -->
            <div class="flex flex-col items-center space-y-3 py-3">
              <!-- Settings -->
              <a href="form-layout-5.html" class="flex size-11 items-center justify-center rounded-lg outline-none transition-colors duration-200 hover:bg-primary/20 focus:bg-primary/20 active:bg-primary/25 dark:hover:bg-navy-300/20 dark:focus:bg-navy-300/20 dark:active:bg-navy-300/25">
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
                          911 Emergency System
                        </a>
                        <p class="text-xs text-slate-400 dark:text-navy-300">
                          Administrator
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
                      <a href="#" class="group flex items-center space-x-3 py-2 px-4 tracking-wide outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600">
                        <div class="flex size-8 items-center justify-center rounded-lg bg-error text-white">
                          <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                          </svg>
                        </div>

                        <div>
                          <h2 class="font-medium text-slate-700 transition-colors group-hover:text-primary group-focus:text-primary dark:text-navy-100 dark:group-hover:text-accent-light dark:group-focus:text-accent-light">
                            Reports
                          </h2>
                          <div class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                            Generate reports
                          </div>
                        </div>
                      </a>
                      <a href="#" class="group flex items-center space-x-3 py-2 px-4 tracking-wide outline-none transition-all hover:bg-slate-100 focus:bg-slate-100 dark:hover:bg-navy-600 dark:focus:bg-navy-600">
                        <div class="flex size-8 items-center justify-center rounded-lg bg-success text-white">
                          <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          </svg>
                        </div>

                        <div>
                          <h2 class="font-medium text-slate-700 transition-colors group-hover:text-primary group-focus:text-primary dark:text-navy-100 dark:group-hover:text-accent-light dark:group-focus:text-accent-light">
                            Settings
                          </h2>
                          <div class="text-xs text-slate-400 line-clamp-1 dark:text-navy-300">
                            Call Logs settings
                          </div>
                        </div>
                      </a>
                      <a href="logout.php">
                      <div class="mt-3 px-4">
                        <button class="btn h-9 w-full space-x-2 bg-primary text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                          <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewbox="0 0 24 24" stroke="currentColor" >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" href="logout.php"></path>
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
                    <input placeholder="Search here..." class="form-input peer h-full rounded-full bg-slate-150 px-4 pl-9 text-xs+ text-slate-800 ring-primary/50 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:text-navy-100 dark:placeholder-navy-300 dark:ring-accent/50 dark:hover:bg-navy-900 dark:focus:bg-navy-900" :class="isShowPopper ? 'w-80' : 'w-60'" @focus="isShowPopper= true" type="text" x-ref="popperRef" id="searchInput" 
                    onkeyup="searchTable()">

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

              <!-- Notification-->
              
              <!-- Right Sidebar Toggle -->
            </div>
          </div>
        </div>
      </nav>

      <!-- Mobile Searchbar -->
      <!-- <div x-show="$store.breakpoints.isXs && $store.global.isSearchbarActive" x-transition:enter="easy-out transition-all" x-transition:enter-start="opacity-0 scale-105" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="easy-in transition-all" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 z-[100] flex flex-col bg-white dark:bg-navy-700 sm:hidden">
        <div class="flex items-center space-x-2 bg-slate-100 px-3 pt-2 dark:bg-navy-800">
          <button class="btn -ml-1.5 size-7 shrink-0 rounded-full p-0 text-slate-600 hover:bg-slate-300/20 active:bg-slate-300/25 dark:text-navy-100 dark:hover:bg-navy-300/20 dark:active:bg-navy-300/25" @click="$store.global.isSearchbarActive = false">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" stroke-width="1.5" viewbox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>
          <input x-effect="$store.global.isSearchbarActive && $nextTick(() => $el.focus() );" class="form-input h-8 w-full bg-transparent placeholder-slate-400 dark:placeholder-navy-300" type="text" placeholder="Search here...">
        </div>

        <div x-data="{activeTab:'tabAll'}" class="is-scrollbar-hidden flex shrink-0 overflow-x-auto bg-slate-100 px-2 text-slate-600 dark:bg-navy-800 dark:text-navy-200">
          <button @click="activeTab = 'tabAll'" :class="activeTab === 'tabAll' ? 'border-primary dark:border-accent text-primary dark:text-accent-light' : 'border-transparent hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'" class="btn shrink-0 rounded-none border-b-2 px-3.5 py-2.5">
            All
          </button>
          <button @click="activeTab = 'tabFiles'" :class="activeTab === 'tabFiles' ? 'border-primary dark:border-accent text-primary dark:text-accent-light' : 'border-transparent hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'" class="btn shrink-0 rounded-none border-b-2 px-3.5 py-2.5">
            Files
          </button>
          <button @click="activeTab = 'tabChats'" :class="activeTab === 'tabChats' ? 'border-primary dark:border-accent text-primary dark:text-accent-light' : 'border-transparent hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'" class="btn shrink-0 rounded-none border-b-2 px-3.5 py-2.5">
            Chats
          </button>
          <button @click="activeTab = 'tabEmails'" :class="activeTab === 'tabEmails' ? 'border-primary dark:border-accent text-primary dark:text-accent-light' : 'border-transparent hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'" class="btn shrink-0 rounded-none border-b-2 px-3.5 py-2.5">
            Emails
          </button>
          <button @click="activeTab = 'tabProjects'" :class="activeTab === 'tabProjects' ? 'border-primary dark:border-accent text-primary dark:text-accent-light' : 'border-transparent hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'" class="btn shrink-0 rounded-none border-b-2 px-3.5 py-2.5">
            Projects
          </button>
          <button @click="activeTab = 'tabTasks'" :class="activeTab === 'tabTasks' ? 'border-primary dark:border-accent text-primary dark:text-accent-light' : 'border-transparent hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'" class="btn shrink-0 rounded-none border-b-2 px-3.5 py-2.5">
            Tasks
          </button>
        </div>

        <div class="is-scrollbar-hidden overflow-y-auto overscroll-contain pb-2">
          <div class="is-scrollbar-hidden mt-3 flex space-x-4 overflow-x-auto px-3">
            <a href="apps-kanban.html" class="w-14 text-center">
              <div class="avatar size-12">
                <div class="is-initial rounded-full bg-success text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                  </svg>
                </div>
              </div>
              <p class="mt-1.5 overflow-hidden text-ellipsis whitespace-nowrap text-xs text-slate-700 dark:text-navy-100">
                Kanban
              </p>
            </a>
            <a href="dashboards-crm-analytics.html" class="w-14 text-center">
              <div class="avatar size-12">
                <div class="is-initial rounded-full bg-secondary text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                  </svg>
                </div>
              </div>
              <p class="mt-1.5 overflow-hidden text-ellipsis whitespace-nowrap text-xs text-slate-700 dark:text-navy-100">
                Analytics
              </p>
            </a>
            <a href="apps-chat.html" class="w-14 text-center">
              <div class="avatar size-12">
                <div class="is-initial rounded-full bg-info text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                  </svg>
                </div>
              </div>
              <p class="mt-1.5 overflow-hidden text-ellipsis whitespace-nowrap text-xs text-slate-700 dark:text-navy-100">
                Chat
              </p>
            </a>
            <a href="apps-filemanager.html" class="w-14 text-center">
              <div class="avatar size-12">
                <div class="is-initial rounded-full bg-error text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                  </svg>
                </div>
              </div>
              <p class="mt-1.5 overflow-hidden text-ellipsis whitespace-nowrap text-xs text-slate-700 dark:text-navy-100">
                Files
              </p>
            </a>
            <a href="dashboards-crypto-1.html" class="w-14 text-center">
              <div class="avatar size-12">
                <div class="is-initial rounded-full bg-secondary text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
              </div>
              <p class="mt-1.5 overflow-hidden text-ellipsis whitespace-nowrap text-xs text-slate-700 dark:text-navy-100">
                Crypto
              </p>
            </a>
            <a href="dashboards-banking-1.html" class="w-14 text-center">
              <div class="avatar size-12">
                <div class="is-initial rounded-full bg-primary text-white dark:bg-accent">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
                  </svg>
                </div>
              </div>
              <p class="mt-1.5 overflow-hidden text-ellipsis whitespace-nowrap text-xs text-slate-700 dark:text-navy-100">
                Banking
              </p>
            </a>
            <a href="apps-todo.html" class="w-14 text-center">
              <div class="avatar size-12">
                <div class="is-initial rounded-full bg-info text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12.5293 18L20.9999 8.40002" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M3 13.2L7.23529 18L17.8235 6" stroke-linecap="round" stroke-linejoin="round"></path>
                  </svg>
                </div>
              </div>
              <p class="mt-1.5 overflow-hidden text-ellipsis whitespace-nowrap text-xs text-slate-700 dark:text-navy-100">
                Todo
              </p>
            </a>

            <a href="dashboards-orders.html" class="w-14 text-center">
              <div class="avatar size-12">
                <div class="is-initial rounded-full bg-warning text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                </div>
              </div>
              <p class="mt-1.5 overflow-hidden text-ellipsis whitespace-nowrap text-xs text-slate-700 dark:text-navy-100">
                Orders
              </p>
            </a>
          </div>

          <div class="mt-3 flex items-center justify-between bg-slate-100 py-1.5 px-3 dark:bg-navy-800">
            <p class="text-xs uppercase text-slate-400 dark:text-navy-300">
              Recent
            </p>
            <a href="#" class="text-tiny+ font-medium uppercase text-primary outline-none transition-colors duration-300 hover:text-primary/70 focus:text-primary/70 dark:text-accent-light dark:hover:text-accent-light/70 dark:focus:text-accent-light/70">
              View All
            </a>
          </div>

          <div class="mt-1 font-inter font-medium">
            <a class="group flex items-center space-x-2 px-2.5 py-2 tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100" href="apps-chat.html">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 transition-colors group-hover:text-slate-500 group-focus:text-slate-500 dark:text-navy-300 dark:group-hover:text-navy-200 dark:group-focus:text-navy-200" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
              <span>Chat App</span>
            </a>
            <a class="group flex items-center space-x-2 px-2.5 py-2 tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100" href="apps-filemanager.html">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 transition-colors group-hover:text-slate-500 group-focus:text-slate-500 dark:text-navy-300 dark:group-hover:text-navy-200 dark:group-focus:text-navy-200" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
              </svg>
              <span>File Manager App</span>
            </a>
            <a class="group flex items-center space-x-2 px-2.5 py-2 tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100" href="apps-mail.html">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 transition-colors group-hover:text-slate-500 group-focus:text-slate-500 dark:text-navy-300 dark:group-hover:text-navy-200 dark:group-focus:text-navy-200" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
              </svg>
              <span>Email App</span>
            </a>
            <a class="group flex items-center space-x-2 px-2.5 py-2 tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100" href="apps-kanban.html">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 transition-colors group-hover:text-slate-500 group-focus:text-slate-500 dark:text-navy-300 dark:group-hover:text-navy-200 dark:group-focus:text-navy-200" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
              </svg>
              <span>Kanban Board</span>
            </a>
            <a class="group flex items-center space-x-2 px-2.5 py-2 tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100" href="apps-todo.html">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 transition-colors group-hover:text-slate-500 group-focus:text-slate-500 dark:text-navy-300 dark:group-hover:text-navy-200 dark:group-focus:text-navy-200" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path d="M3 13.2L7.23529 18L17.8235 6" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M12.5293 18L20.9999 8.40002" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span>Todo App</span>
            </a>
            <a class="group flex items-center space-x-2 px-2.5 py-2 tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100" href="dashboards-crypto-2.html">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 transition-colors group-hover:text-slate-500 group-focus:text-slate-500 dark:text-navy-300 dark:group-hover:text-navy-200 dark:group-focus:text-navy-200" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>

              <span>Crypto Dashboard</span>
            </a>
            <a class="group flex items-center space-x-2 px-2.5 py-2 tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100" href="dashboards-banking-2.html">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 transition-colors group-hover:text-slate-500 group-focus:text-slate-500 dark:text-navy-300 dark:group-hover:text-navy-200 dark:group-focus:text-navy-200" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path>
              </svg>

              <span>Banking Dashboard</span>
            </a>
            <a class="group flex items-center space-x-2 px-2.5 py-2 tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100" href="dashboards-crm-analytics.html">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 transition-colors group-hover:text-slate-500 group-focus:text-slate-500 dark:text-navy-300 dark:group-hover:text-navy-200 dark:group-focus:text-navy-200" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>

              <span>Analytics Dashboard</span>
            </a>
            <a class="group flex items-center space-x-2 px-2.5 py-2 tracking-wide outline-none transition-all hover:bg-slate-100 hover:text-slate-800 focus:bg-slate-100 focus:text-slate-800 dark:hover:bg-navy-600 dark:hover:text-navy-100 dark:focus:bg-navy-600 dark:focus:text-navy-100" href="dashboards-influencer.html">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4.5 text-slate-400 transition-colors group-hover:text-slate-500 group-focus:text-slate-500 dark:text-navy-300 dark:group-hover:text-navy-200 dark:group-focus:text-navy-200" fill="none" viewbox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>

              <span>Influencer Dashboard</span>
            </a>
          </div>
        </div>
      </div> -->

      <!-- Right Sidebar -->

      <!-- Main Content Wrapper -->

      <style>
        .containerGrid{ height: 100%; font-size: 30px; text-align: center; display: grid; grid-template-columns: auto auto auto; margin-left: 10px; padding: 1rem;}
      </style>
      <!-- Main Content Wrapper -->
      <main class="main-content w-full px-[var(--margin-x)] pb-8">
          
          <div class="container mt-4">
              <div class="containerGrid">
                  <!-- Police Card -->
                  <div>
                      <div>
                          <div class="bg-primary text-white">
                              Police
                          </div>
                          <div style="padding-bottom: 5rem;">
                              <h5 class="card-title" id="police-count"><?php echo $counts['Police']; ?></h5>
                              <p class="card-text">Total police-related calls.</p>
                          </div>
                      </div>
                  </div>

                  <!-- Fire Card -->
                  <div>
                      <div>
                          <div class="text-danger text-white" style="background-color: red;">
                              Fire
                          </div>
                          <div class="card-body">
                              <h5 class="card-title" id="fire-count"><?php echo $counts['Fire']; ?></h5>
                              <p class="card-text">Total fire-related calls.</p>
                          </div>
                      </div>
                  </div>

                  <!-- EMS Card -->
                  <div>
                      <div>
                          <div class="bg-success text-white">
                              EMS
                          </div>
                          <div class="card-body">
                              <h5 class="card-title" id="ems-count"><?php echo $counts['EMS']; ?></h5>
                              <p class="card-text">Total EMS-related calls.</p>
                          </div>
                      </div>
                  </div>

                  <!-- Rescue Card -->
                  <div>
                      <div>
                          <div class="bg-warning text-white">
                              Rescue
                          </div>
                          <div class="card-body">
                              <h5 class="card-title" id="rescue-count"><?php echo $counts['Rescue']; ?></h5>
                              <p class="card-text">Total rescue-related calls.</p>
                          </div>
                      </div>
                  </div>

                  <!-- Other Card -->
                  <div>
                      <div>
                          <div class="bg-secondary text-white">
                              Other
                          </div>
                          <div class="card-body">
                              <h5 class="card-title" id="other-count"><?php echo $counts['Other']; ?></h5>
                              <p class="card-text">Total other-related calls.</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

        <!-- Call Logs-->
        <div class="flex items-center space-x-4 py-5 lg:py-6">
          <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">
            Call Logs
          </h2>
        </div>

        <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
          <table class="is-zebra w-full text-left" id="callLogTable">
            <thead>
              <tr>
                <th class="whitespace-nowrap rounded-l-lg bg-slate-200 px-3 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">#</th>
                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Agent Name</th>
                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Type of Service</th>
                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Call Type</th>
                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Call Date</th>
                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Call Time</th>
                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Contact Number</th>
                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Call Count</th>
                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Name</th>
                <th class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Age</th>
                <th class="whitespace-nowrap rounded-r-lg bg-slate-200 px-3 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">Location</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // Define the number of rows per page
                $rows_per_page = 10;

                // Get the current page from the URL, default to 1 if not set
                $page = isset($_GET['page']) ? $_GET['page'] : 1;

                // Calculate the offset for the query
                $offset = ($page - 1) * $rows_per_page;

                // Query to get the total number of rows
                $count_query = "SELECT COUNT(*) AS total_rows FROM call_logs";
                $count_result = mysqli_query($conn, $count_query);
                $count_row = mysqli_fetch_assoc($count_result);
                $total_rows = $count_row['total_rows'];

                // Calculate the total number of pages
                $total_pages = ceil($total_rows / $rows_per_page);

                // Query to get call logs with necessary data, including agent's full name
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
                      cl.remarks,
                      CONCAT(u.first_name, ' ', u.middle_initial, '. ', u.last_name) AS full_name
                  FROM call_logs cl
                  LEFT JOIN service_types st ON cl.type_of_service = st.id
                  LEFT JOIN call_types ct ON cl.call_type = ct.id
                  LEFT JOIN users u ON cl.agent_id = u.id
                 
                ";
                $result = mysqli_query($conn, $query);

                $contactNumberCounts = [];
                $no = 1;

                if (mysqli_num_rows($result) > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                    $contactNumber = $row['contact_number'];
                    if (isset($contactNumberCounts[$contactNumber])) {
                      $contactNumberCounts[$contactNumber]++;
                    } else {
                      $contactNumberCounts[$contactNumber] = 1;
                    }

                    echo "<tr class='clickable-row' 
                            data-agent-id='{$row['full_name']}' 
                            data-service-type='{$row['service_type']}' 
                            data-call-type='{$row['call_type']}' 
                            data-call-date='{$row['call_date']}' 
                            data-call-time='{$row['call_time']}' 
                            data-contact-number='{$row['contact_number']}' 
                            data-count='{$contactNumberCounts[$contactNumber]}'
                            data-name='{$row['name']}' 
                            data-age='{$row['age']}' 
                            data-location='{$row['location']}'>
                            <td class='whitespace-nowrap rounded-l-lg px-4 py-3 sm:px-5'>{$no}</td>
                            <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['full_name']}</td>
                            <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['service_type']}</td>
                            <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['call_type']}</td>
                            <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['call_date']}</td>
                            <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['call_time']}</td>
                            <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['contact_number']}</td>
                            <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$contactNumberCounts[$contactNumber]}</td>
                            <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['name']}</td>
                            <td class='whitespace-nowrap px-4 py-3 sm:px-5'>{$row['age']}</td>
                            <td class='whitespace-nowrap rounded-r-lg px-4 py-3 sm:px-5'>{$row['location']}</td>
                          </tr>";

                    $no++;
                  }
                }
              ?>
            </tbody>
          </table>

          <!-- Pagination -->
          <!-- <div class="pagination">
            <ul class="pagination-list"> -->
              <!-- First page link -->
              <!-- <li class="pagination-item">
                <a href="?page=1" class="pagination-link">First</a>
              </li> -->

              <!-- Previous page link -->
              <!-- <li class="pagination-item <?php if ($page <= 1) echo 'disabled'; ?>">
                <a href="?page=<?php echo max(1, $page - 1); ?>" class="pagination-link">Previous</a>
              </li> -->

              <!-- Next page link -->
              <!-- <li class="pagination-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                <a href="?page=<?php echo min($total_pages, $page + 1); ?>" class="pagination-link">Next</a>
              </li> -->

              <!-- Last page link -->
              <!-- <li class="pagination-item">
                <a href="?page=<?php echo $total_pages; ?>" class="pagination-link">Last</a>
              </li> -->
            <!-- </ul>
          </div> -->
        </div>

      </main>
    </div>
    <!-- 
        This is a place for Alpine.js Teleport feature 
        @see https://alpinejs.dev/directives/teleport
      -->
    <div id="x-teleport-target"></div>
    
  </body>
</html>


<script>
    document.addEventListener("DOMContentLoaded", () => {
      Alpine.start();
    });

    // Script for opening modal with row data
    document.querySelectorAll('.clickable-row').forEach(row => {
      row.addEventListener('click', function() {
        // Get data from the clicked row
        const rowData = {
          agentId: this.getAttribute('data-agent-id'),
          serviceType: this.getAttribute('data-service-type'),
          callType: this.getAttribute('data-call-type'),
          callDate: this.getAttribute('data-call-date'),
          callTime: this.getAttribute('data-call-time'),
          contactNumber: this.getAttribute('data-contact-number'),
          callCount: this.getAttribute('data-count'),
          name: this.getAttribute('data-name'),
          age: this.getAttribute('data-age'),
          location: this.getAttribute('data-location')
        };

        // Set the row data into the modal using Alpine store
        Alpine.store('showModal', true);
        Alpine.store('rowData', rowData);
      });
    });

    // Script for search functionality
    function searchTable() {
      // Get the search input value
      let input = document.getElementById('searchInput').value.toLowerCase();

      // Get the table and rows
      let table = document.getElementById('callLogTable');
      let rows = table.getElementsByTagName('tr');

      // Loop through all rows
      for (let i = 1; i < rows.length; i++) {  // Start from 1 to skip the header row
        let row = rows[i];

        // Get the data attributes for each row
        let agentId = row.getAttribute('data-agent-id').toLowerCase();
        let serviceType = row.getAttribute('data-service-type').toLowerCase();
        let callType = row.getAttribute('data-call-type').toLowerCase();
        let callDate = row.getAttribute('data-call-date').toLowerCase(); // Ensure it is lowercase for comparison
        let callTime = row.getAttribute('data-call-time').toLowerCase();
        let contactNumber = row.getAttribute('data-contact-number').toLowerCase();
        let callCount = row.getAttribute('data-count').toLowerCase();
        let name = row.getAttribute('data-name').toLowerCase();
        let age = row.getAttribute('data-age').toLowerCase();
        let location = row.getAttribute('data-location').toLowerCase();

        // Convert numeric search input for comparison (e.g., age or callCount)
        let numericInput = parseInt(input);
        let numericAge = parseInt(age);
        let numericCallCount = parseInt(callCount);

        // Check if the search input matches any of the data attributes
        if (
          agentId.includes(input) ||
          serviceType.includes(input) ||
          callType.includes(input) ||
          callDate.includes(input) ||
          callTime.includes(input) ||
          contactNumber.includes(input) ||
          name.includes(input) ||
          location.includes(input) ||
          numericAge == numericInput || // Match age as number
          numericCallCount == numericInput  // Match call count as number
        ) {
          row.style.display = '';  // Show row if it matches
        } else {
          row.style.display = 'none';  // Hide row if it doesn't match
        }
      }
    }
  </script>

