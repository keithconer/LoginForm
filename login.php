<?php
session_start();
require_once 'config/db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_number = trim($_POST['account_number']);
    $password = $_POST['password'];
    
    if (empty($account_number) || empty($password)) {
        $error = "All fields are required";
    } else {
        $hashed_password = md5($password);
        
        $query = "SELECT * FROM users WHERE account_number = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $account_number, $hashed_password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Set session variables
            $_SESSION['user_id'] = $user['account_number'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            
            // Redirect based on user type
            if ($user['user_type'] === 'Admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: employee/dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid account number or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Login:</h1>
            <h2>Welcome Back!</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="post" action="">
                <div class="input-group">
                    <input type="text" name="account_number" placeholder="Account Number" required>
                </div>
                
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                
                <button type="submit">Log In</button>
            </form>
            
            <a href="register.php" class="link">Create an Account?</a>
        </div>
    </div>
</body>
</html>