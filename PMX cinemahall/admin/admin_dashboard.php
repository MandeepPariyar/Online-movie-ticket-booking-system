<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

<div class="admin-dashboard">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
    <p>Manage movies, bookings, and more.</p>
    
    <a href="manage_movies.php" class="dashboard-btn">Manage Movies</a>
    <a href="manage_showtimes.php" class="dashboard-btn">Manage Showtimes</a>
    <a href="manage_bookings.php" class="dashboard-btn">Manage Bookings</a>
    <a href="payment_history.php" class="dashboard-btn">Payment History</a>
    <a href="admin_logout.php" class="dashboard-btn logout">Logout</a>
</div>

</body>
</html>
