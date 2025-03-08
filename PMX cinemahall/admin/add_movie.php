<?php
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $poster = trim($_POST['poster']); // Assuming a URL or file path
    $description = trim($_POST['description']);
    $release_date = $_POST['release_date'];

    $sql = "INSERT INTO movies (title, poster, description, release_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $poster, $description, $release_date);

    if ($stmt->execute()) {
        echo "<script>alert('Movie added successfully!'); window.location.href='manage_movies.php';</script>";
    } else {
        echo "<script>alert('Error adding movie.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="stylesheet" href="add_movie.css">

</head>
<body>

<div class="admin-dashboard">
    <h1>Add New Movie</h1>
    <form method="POST">
        <input type="text" name="title" placeholder="Movie Title" required>
        <input type="text" name="poster" placeholder="Poster URL" required>
        <textarea name="description" placeholder="Movie Description" required></textarea>
        <input type="date" name="release_date" required>
        <button type="submit">Add Movie</button>
    </form>
    <a href="manage_movies.php" class="dashboard-btn">Back</a>
</div>

</body>
</html>
