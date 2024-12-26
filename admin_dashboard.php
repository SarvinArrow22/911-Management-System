<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - 911 Emergency System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* Basic styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }
        /* Header Style */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #6d5dfc;
            color: white;
            position: fixed;
            top: 0;
            left: 201px;
            right: 0;
            z-index: 10;
        }
        .header h1 {
            margin: 0;
        }
        .side-panel {
            width: 200px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            z-index: 5;
        }
        .side-panel .logo {
            display: block;
            margin: 0 auto;
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
        }
        .side-panel ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .side-panel ul li {
            padding: 10px;
            text-align: center;
        }
        .side-panel ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }
        /* Content Adjustment */
        .content {
            margin-left: 220px; /* Adjust for side panel width */
            margin-top: 70px; /* Adjust for fixed header */
            padding: 20px;
        }
        .modal-header {
            background-color: #6d5dfc;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>911 Emergency System</h1>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <?= $_SESSION['full_name'] ?>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Side Panel -->
    <div class="side-panel">
        <img src="assets/images/911 Official Logo.webp" alt="Logo" class="logo">
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="#callLogSection">Call Logs</a></li>
            <li><a href="#reportsSection">Reports</a></li>
            <li><a href="admin-settings.php">Settings</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content" style="border: 1px solid crimson;">
        <h2>Admin Dashboard</h2>
        <div id="callLogSection">
            <h3>Call Logs</h3>
            <div style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered table-striped" id="callLogTable">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Agent ID</th>
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
                                        data-agent-id='{$row['agent_id']}' 
                                        data-service-type='{$row['service_type']}' 
                                        data-call-type='{$row['call_type']}' 
                                        data-call-date='{$row['call_date']}' 
                                        data-call-time='{$row['call_time']}' 
                                        data-contact-number='{$row['contact_number']}' 
                                        data-count='{$contactNumberCounts[$contactNumber]}'
                                        data-name='{$row['name']}' 
                                        data-age='{$row['age']}' 
                                        data-location='{$row['location']}'>
                                        <td>{$no}</td>
                                        <td>{$row['agent_id']}</td>
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
                            echo "<tr><td colspan='11' class='text-center'>No records found</td></tr>";
                        }
                        mysqli_close($conn);
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Structure -->
        <div class="modal fade" id="callLogModal" tabindex="-1" aria-labelledby="callLogModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="callLogModalLabel">Call Log Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul>
                            <li><strong>Agent ID: </strong><span id="modal-agent-id"></span></li>
                            <li><strong>Service Type: </strong><span id="modal-service-type"></span></li>
                            <li><strong>Call Type: </strong><span id="modal-call-type"></span></li>
                            <li><strong>Call Date: </strong><span id="modal-call-date"></span></li>
                            <li><strong>Call Time: </strong><span id="modal-call-time"></span></li>
                            <li><strong>Contact Number: </strong><span id="modal-contact-number"></span></li>
                            <li><strong>Call Count: </strong><span id="modal-call-count"></span></li>
                            <li><strong>Name: </strong><span id="modal-name"></span></li>
                            <li><strong>Age: </strong><span id="modal-age"></span></li>
                            <li><strong>Location: </strong><span id="modal-location"></span></li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery for handling row click and populating the modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('#callLogTable').addEventListener('click', function (e) {
                const row = e.target.closest('.clickable-row');
                if (!row) return;

                const agentId = row.getAttribute('data-agent-id');
                const serviceType = row.getAttribute('data-service-type');
                const callType = row.getAttribute('data-call-type');
                const callDate = row.getAttribute('data-call-date');
                const callTime = row.getAttribute('data-call-time');
                const contactNumber = row.getAttribute('data-contact-number');
                const count = row.getAttribute('data-count');
                const name = row.getAttribute('data-name');
                const age = row.getAttribute('data-age');
                const location = row.getAttribute('data-location');

                // Populate modal with data
                document.getElementById('modal-agent-id').innerText = agentId;
                document.getElementById('modal-service-type').innerText = serviceType;
                document.getElementById('modal-call-type').innerText = callType;
                document.getElementById('modal-call-date').innerText = callDate;
                document.getElementById('modal-call-time').innerText = callTime;
                document.getElementById('modal-contact-number').innerText = contactNumber;
                document.getElementById('modal-call-count').innerText = count;
                document.getElementById('modal-name').innerText = name;
                document.getElementById('modal-age').innerText = age;
                document.getElementById('modal-location').innerText = location;

                // Show the modal
                new bootstrap.Modal(document.getElementById('callLogModal')).show();
            });
        });
    </script>
</body>
</html>
