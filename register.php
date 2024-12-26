<?php
// Include the database connection file
include 'includes/db.php';

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
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Register</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="register.php">
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
        <a href="login.php" class="btn btn-link">Already have an account? Login</a>
    </form>
</div>
</body>
</html>
