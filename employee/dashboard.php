<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in and is an employee
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Employee') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="align-items: flex-start; padding-top: 50px;">
    <div class="dashboard">
        <div class="dashboard-header">
            <h1 class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <a href="../logout.php" class="logout-btn">Logout</a>
        </div>
        
        <div class="employee-content">
            <h2>Employee Dashboard</h2>
            <div class="employee-info">
        
            </div>
        </div>
    </div>
</body>
</html>