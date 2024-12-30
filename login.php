<?php
session_start();
include 'includes/db.php'; // Database connection file

// Initialize variables
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $selected_team = $_POST['dropdown']; // Get selected team

    if (empty($username) || empty($password) || empty($selected_team)) {
        $error = "Please enter both username, password, and select a team.";
    } else {
        // Query to get user information and their associated team
        $sql = "SELECT id, first_name, last_name, password, team, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $first_name, $last_name, $hashed_password, $team, $role);
                $stmt->fetch();

                // Validate password and team selection
                if (password_verify($password, $hashed_password)) {
                    if ($selected_team == $team) { // Check if selected team matches user's team
                        // Store user session data including team
                        $_SESSION['user_id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['role'] = $role;
                        $_SESSION['full_name'] = $first_name . ' ' . $last_name;
                        $_SESSION['team'] = $team; // Store team information

                        // Redirect based on role
                        if ($role === 'admin') {
                            header("Location: admin_dashboard.php");
                            exit();
                        } else if ($role === 'user') {
                            header("Location: user_dashboardTeam.php");
                            exit();
                        } else {
                            $error = "Unknown role. Please contact the administrator.";
                        }
                    } else {
                        $error = "The selected team does not match your assigned team.";
                    }
                } else {
                    $error = "Invalid password. Please try again.";
                }
            } else {
                $error = "No account found with that username.";
            }
            $stmt->close();
        } else {
            $error = "Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - 911 Emergency System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6d5dfc, #46aef7);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: #6d5dfc;
            color: white;
            text-align: center;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .btn-primary {
            background: #46aef7;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background: #3498db;
        }
        .footer-text {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3>911 Emergency System</h3>
                        <p>Login to your account</p>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter Username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
                            </div>
                            <div class="mb-3">
                                <label for="dropdown" class="form-label">Choose Team</label>
                                <select name="dropdown" id="dropdown" class="form-control" required>
                                    <option value="" disabled selected>Select a team</option>
                                    <option value="alpha">Alpha</option>
                                    <option value="bravo">Bravo</option>
                                    <option value="charlie">Charlie</option>
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                        </form>

                    </div>
                    <div class="card-footer text-center">
                        <small class="footer-text">Don't have an account? <a href="register.php">Register</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
