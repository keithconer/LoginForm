<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../login.php");
    exit;
}

// Get all users
$query = "SELECT * FROM users ORDER BY account_number";
$result = $conn->query($query);
$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="align-items: flex-start; padding-top: 50px;">
    <div class="dashboard">
        <div class="dashboard-header">
            <h1 class="welcome-message">Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <a href="../logout.php" class="logout-btn">Logout</a>
        </div>
        
        <div class="nav-tabs">
            <div class="nav-tab active" onclick="showTab('dashboard')">Dashboard</div>
            <div class="nav-tab" onclick="showTab('manage-accounts')">Manage Accounts</div>
        </div>
        
        <div id="dashboard" class="tab-content active">
            <h2>Admin Dashboard</h2>
            <p>This is the main admin dashboard. You can view system statistics and manage user accounts.</p>
            <div class="stats">
                <p>Total Users: <?php echo count($users); ?></p>
                <p>Admin Users: <?php echo count(array_filter($users, function($user) { return $user['user_type'] === 'Admin'; })); ?></p>
                <p>Employee Users: <?php echo count(array_filter($users, function($user) { return $user['user_type'] === 'Employee'; })); ?></p>
            </div>
        </div>
        
        <div id="manage-accounts" class="tab-content">
            <h2>Manage Accounts</h2>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Account Number</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Password (Encrypted)</th>
                        <th>User Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['account_number']; ?></td>
                        <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo $user['password']; ?></td>
                        <td><?php echo $user['user_type']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        function showTab(tabId) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all nav tabs
            document.querySelectorAll('.nav-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show the selected tab
            document.getElementById(tabId).classList.add('active');
            
            // Add active class to the clicked nav tab
            event.target.classList.add('active');
        }
    </script>
</body>
</html>