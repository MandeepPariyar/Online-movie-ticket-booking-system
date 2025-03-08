<?php
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if a movie ID is provided
if (isset($_GET['id'])) {
    $movie_id = $_GET['id'];

    // Fetch movie poster URL before deleting (optional)
    $poster_sql = "SELECT poster FROM movies WHERE id = ?";
    $stmt = $conn->prepare($poster_sql);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $poster_path = "";

    if ($row = $result->fetch_assoc()) {
        $poster_path = $row['poster'];
    }

    // Delete movie from the database
    $delete_sql = "DELETE FROM movies WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $movie_id);

    if ($stmt->execute()) {
        // Optional: Delete poster file if stored locally
        if (file_exists($poster_path)) {
            unlink($poster_path);
        }
        
        echo "<script>alert('Movie deleted successfully!'); window.location.href='manage_movies.php';</script>";
    } else {
        echo "<script>alert('Error deleting movie.'); window.history.back();</script>";
    }
} else {
    header("Location: manage_movies.php");
    exit();
}
?>
