<?php
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all movies
$sql = "SELECT * FROM movies ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Movies</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="manage_movies.css">

</head>
<body>

<div class="admin-dashboard">
    <h1>Manage Movies</h1>
    
    <a href="admin_dashboard.php" class="dashboard-btn">Back to Dashboard</a>
    <a href="add_movie.php" class="dashboard-btn">Add New Movie</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Poster</th>
                <th>Release Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($movie = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $movie['id']; ?></td>
                    <td><?php echo htmlspecialchars($movie['title']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($movie['poster']); ?>" width="50"></td>
                    <td><?php echo htmlspecialchars($movie['release_date']); ?></td>
                    <td>
                        <a href="edit_movie.php?id=<?php echo $movie['id']; ?>" class="edit-btn">Edit</a>
                        <a href="delete_movie.php?id=<?php echo $movie['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
