<?php
session_start();
require_once 'config/db_connect.php';

$error = '';
$first_name = $last_name = $username = $password = $confirm_password = $user_type = '';

// Get the next account number
$query = "SELECT MAX(account_number) as max_account FROM users";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$next_account_number = ($row['max_account'] ?? 0) + 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type'];
    
    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($username) || empty($password) || empty($confirm_password) || empty($user_type)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error = "Password inputs do not match";
    } elseif (strlen($password) < 8 || strlen($password) > 20 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = "Password must contain letters, numbers, and be 8-20 characters long";
    } else {
        // Check if username already exists
        $check_query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Username already exists";
        } else {
            // Insert new user
            $hashed_password = md5($password);
            $insert_query = "INSERT INTO users (first_name, last_name, username, password, user_type) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("sssss", $first_name, $last_name, $username, $hashed_password, $user_type);
            
            if ($stmt->execute()) {
                // Redirect to login page
                header("Location: login.php");
                exit;
            } else {
                $error = "Registration failed: " . $conn->error;
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
    <title>Registration</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Registration:</h1>
            <h2>Create an Account!</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="post" action="">
                <div class="row">
                    <div class="input-group">
                        <input type="text" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                    </div>
                    <div class="input-group">
                        <input type="text" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                    </div>
                </div>
                
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                
                <div class="input-group">
                    <p>Your Account Number will be: <?php echo $next_account_number; ?></p>
                </div>
                
                <div class="row">
                    <div class="input-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                </div>
                
                <div class="input-group">
                    <label for="user_type">User type:</label>
                    <select name="user_type" id="user_type" required>
                        <option value="">Select user type</option>
                        <option value="Admin" <?php if ($user_type === 'Admin') echo 'selected'; ?>>Admin</option>
                        <option value="Employee" <?php if ($user_type === 'Employee') echo 'selected'; ?>>Employee</option>
                    </select>
                </div>
                
                <button type="submit">Register Account</button>
            </form>
            
            <a href="login.php" class="link">Already have an account? Login!</a>
        </div>
    </div>
</body>
</html>