<?php
// Include database connection
include('includes/db.php');

// Check if admin is logged in
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");  // Redirect to login if not an admin
    exit();
}

// Initialize variables for error and success messages
$error = "";
$success = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize inputs
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $middle_initial = htmlspecialchars(trim($_POST['middle_initial']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $address = htmlspecialchars(trim($_POST['address']));
    $mobile_number = htmlspecialchars(trim($_POST['mobile_number']));
    $email = htmlspecialchars(trim($_POST['email']));
    $role = htmlspecialchars(trim($_POST['role']));
    $team = isset($_POST['team']) ? htmlspecialchars(trim($_POST['team'])) : null;
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Validate required fields
    if (
        empty($first_name) || empty($last_name) || empty($address) || empty($mobile_number) || 
        empty($email) || empty($role) || empty($username) || empty($password)
    ) {
        $error = "All fields are required.";
    } elseif ($role === 'user' && empty($team)) {
        $error = "Please select a team for the user role.";
    } else {
        // Check if the username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username already exists. Please choose another.";
            $stmt->close(); // Close here because we're done with this statement
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL to insert user data
            $sql = "INSERT INTO users 
                    (first_name, middle_initial, last_name, address, mobile_number, email, team, username, password, role) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                // Bind parameters
                $stmt->bind_param(
                    "ssssssssss",
                    $first_name, 
                    $middle_initial, 
                    $last_name, 
                    $address, 
                    $mobile_number, 
                    $email, 
                    $team, 
                    $username, 
                    $hashed_password, 
                    $role
                );

                // Execute the query
                if ($stmt->execute()) {
                    $success = "Registration successful! <a href='login.php'>Login here</a>";
                } else {
                    $error = "Error: " . $stmt->error;
                }
                $stmt->close(); // Close here after execution
            } else {
                $error = "Error preparing statement: " . $conn->error;
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Add custom styling for the settings page */
        .settings-container {
            margin-top: 5px;
        }
        .card-header {
            background-color: #f8f9fa;
        }
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
        <h1>Admin Settings</h1>
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
    <div class="content">
        <div class="container settings-container">
            <!-- User Management Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>User Management</h5>
                </div>
                <div class="card-body">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
                    <!-- User Table -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Mobile Number</th>
                                <th>Email</th>
                                <th>Team</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic User Data from Database -->
                            <?php
                            $result = mysqli_query($conn, "SELECT * FROM users");
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['first_name']}</td>
                                        <td>{$row['last_name']}</td>
                                        <td>{$row['mobile_number']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['team']}</td>
                                        <td>{$row['username']}</td>
                                        <td>{$row['role']}</td>
                                        <td>
                                            <button class='btn btn-info btn-sm'>Edit</button>
                                            <button class='btn btn-danger btn-sm'>Delete</button>
                                        </td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Service Types Management Section -->
            <div class="card mb-4" style="border: 1px solid crimson;">
                <div class="card-header">
                    <h5>Service Types</h5>
                </div>
                <div class="card-body">
                    <!-- Button to trigger Add Service Type Modal -->
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addServiceTypeModal">Add Service Type</button>

                    <!-- Service Types Table -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            // Fetch service types along with their associated call types
                            $query = "
                                SELECT 
                                    service_types.id AS service_type_id, 
                                    service_types.service_type, 
                                    call_types.call_type 
                                FROM service_types 
                                LEFT JOIN call_types ON service_types.id = call_types.service_type_id";  // Corrected join condition

                            $service_types_result = mysqli_query($conn, $query);

                            $service_types = [];  // To store service types and their call types
                            while ($row = mysqli_fetch_assoc($service_types_result)) {
                                // Group the call types under their respective service types
                                $service_types[$row['service_type_id']]['service_type'] = $row['service_type'];
                                $service_types[$row['service_type_id']]['call_types'][] = $row['call_type'];
                            }

                            foreach ($service_types as $service_type_id => $data) {
                                $call_types = implode(', ', $data['call_types']);  // Combine all associated call types
                                echo "<tr>
                                        <td>{$service_type_id}</td>
                                        <td>{$data['service_type']}</td>
                                        <td>
                                            <!-- Edit button -->
                                            <button 
                                                class='btn btn-info btn-sm' 
                                                data-bs-toggle='modal' 
                                                data-bs-target='#editServiceTypeModal' 
                                                data-id='{$service_type_id}' 
                                                data-servicetype='{$data['service_type']}' 
                                                data-calltypes='{$call_types}'>
                                                Edit
                                            </button>
                                            <!-- Delete button -->
                                            <button 
                                                class='btn btn-danger btn-sm' 
                                                onclick=\"window.location.href='delete_service_type.php?id={$service_type_id}';\">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>";
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>

                           <!-- Add Service Type Modal -->
                        <div class="modal fade" id="addServiceTypeModal" tabindex="-1" aria-labelledby="addServiceTypeModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addServiceTypeModalLabel">Add Service Type</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="add_service_type.php">
                                            <div class="form-group">
                                                <label for="service_type">Service Type</label>
                                                <input type="text" id="service_type" name="service_type" class="form-control" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary mt-3">Add Service Type</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
             <!-- Call Types Management Section -->
                <div class="card mb-4" style="border: 1px solid crimson;">
                    <div class="card-header">
                        <h5>Manage Call Types</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCallTypeModal">Add Call Type</button>

                        <!-- Call Types Table -->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Call Type</th>
                                    <th>Associated Service Types</th> <!-- New column -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                // Fetch call types along with their associated service types
                                $query = "
                                    SELECT 
                                        call_types.id AS call_type_id, 
                                        call_types.call_type, 
                                        GROUP_CONCAT(service_types.service_type SEPARATOR ', ') AS associated_service_types 
                                    FROM call_types
                                    LEFT JOIN service_types ON service_types.id = call_types.service_type_id
                                    GROUP BY call_types.id, call_types.call_type";

                                $call_types_result = mysqli_query($conn, $query);

                                $call_type_count = 1; // Initialize a counter variable for sequential numbering

                                while ($row = mysqli_fetch_assoc($call_types_result)) {
                                    echo "<tr>
                                            <td>{$call_type_count}</td>  <!-- Display sequential number -->
                                            <td>{$row['call_type']}</td>
                                            <td>{$row['associated_service_types']}</td>
                                            <td>
                                                <button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#editCallTypeModal' 
                                                    data-id='{$row['call_type_id']}' data-calltype='{$row['call_type']}'>Edit</button>
                                                <button class='btn btn-danger btn-sm' onclick=\"window.location.href='delete_call_type.php?id={$row['call_type_id']}';\">Delete</button>
                                            </td>
                                        </tr>";
                                    $call_type_count++; // Increment the counter for each row
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>



           <!-- Add Call Type Modal -->
           <div class="modal fade" id="addCallTypeModal" tabindex="-1" aria-labelledby="addCallTypeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addCallTypeModalLabel">Add Call Type</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="add_call_type.php">
                                    <div class="form-group">
                                        <label for="service_type_id">Select Service Type</label>
                                        <select id="service_type_id" name="service_type_id" class="form-control" required>
                                            <option value="">Select Service Type</option>
                                            <?php
                                                    // Fetch service types from the database
                                                    $service_types_result = mysqli_query($conn, "SELECT id, service_type FROM service_types");
                                                    while ($row = mysqli_fetch_assoc($service_types_result)) {
                                                        echo "<option value='{$row['id']}'>{$row['service_type']}</option>";
                                                    }
                                                    ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="call_type">Call Type</label>
                                        <input type="text" id="call_type" name="call_type" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-3">Add Call Type</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            <!-- Edit Call Type Modal -->
            <div class="modal fade" id="editCallTypeModal" tabindex="-1" role="dialog" aria-labelledby="editCallTypeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCallTypeModalLabel">Edit Call Type</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="edit_call_type.php">
                                <input type="hidden" name="id" id="edit-call-type-id">
                                <div class="form-group">
                                    <label for="edit_call_type">Call Type</label>
                                    <input type="text" class="form-control" name="call_type" id="edit-call-type" required>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Call Type Modal -->
            <div class="modal fade" id="deleteCallTypeModal" tabindex="-1" role="dialog" aria-labelledby="deleteCallTypeModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteCallTypeModalLabel">Delete Call Type</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this call type?</p>
                            <form method="POST" action="delete_call_type.php">
                                <input type="hidden" name="id" id="delete-call-type-id">
                                <button class='btn btn-danger btn-sm' onclick="window.location.href='delete_call_type.php?id=<?php echo $row['id']; ?>'">Delete</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                                           

        </div>
    </div>

    <!-- Modal for User Registration -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Register New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Registration Form -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php elseif ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <form method="POST" action="admin-dashboard.php">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="middle_initial">Middle Initial</label>
                                <input type="text" class="form-control" name="middle_initial" maxlength="1">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" name="address" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="mobile_number">Mobile Number</label>
                                <input type="text" class="form-control" name="mobile_number" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="role">Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="team">Team</label>
                                <select name="team" class="form-control">
                                    <option value="">Select Team</option>
                                    <option value="Alpha">Alpha</option>
                                    <option value="Bravo">Bravo</option>
                                    <option value="Charlie">Charlie</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Include Bootstrap JS and JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Show team selection only when user role is selected
        $('select[name="role"]').change(function() {
            if ($(this).val() == 'user') {
                $('#team-row').show();
            } else {
                $('#team-row').hide();
            }
        });


            // Populate the Edit Call Type Modal with current data
            $('#editCallTypeModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id'); // Extract info from data-* attributes
                var callType = button.data('calltype');
                
                var modal = $(this);
                modal.find('#edit-call-type-id').val(id);
                modal.find('#edit-call-type').val(callType);
            });

            // Populate the Delete Call Type Modal with the ID
            $('#deleteCallTypeModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id'); // Extract info from data-* attributes
                
                var modal = $(this);
                modal.find('#delete-call-type-id').val(id);
            });

            function confirmDelete(id) {
                if (confirm("Are you sure you want to delete this Call Type?")) {
                    // Redirect to the delete_call_type.php page with the id parameter
                    window.location.href = 'delete_call_type.php?id=' + id;
                }
            }




                // When the call type dropdown changes
                $('#call_type_id').change(function() {
                    var callTypeId = $(this).val();  // Get the selected Call Type ID
                    if (callTypeId) {
                        // Send AJAX request to get the service types for the selected call type
                        $.ajax({
                            url: 'get_service_types.php',  // PHP file to fetch service types based on call type
                            type: 'POST',
                            data: { call_type_id: callTypeId },
                            success: function(response) {
                                // Populate the service type dropdown with the response
                                $('#service_type').html(response);
                            }
                        });
                    } else {
                        // Clear the service type dropdown if no call type is selected
                        $('#service_type').html('<option value="">Select Service Type</option>');
                    }
                });


    </script>
</body>
</html>
