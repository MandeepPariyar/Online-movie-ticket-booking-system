<?php
session_start();
include 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_movies.php");
    exit();
}

$movie_id = $_GET['id'];
$sql = "SELECT * FROM movies WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $poster = trim($_POST['poster']);
    $description = trim($_POST['description']);
    $release_date = $_POST['release_date'];

    $update_sql = "UPDATE movies SET title=?, poster=?, description=?, release_date=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssi", $title, $poster, $description, $release_date, $movie_id);

    if ($stmt->execute()) {
        echo "<script>alert('Movie updated successfully!'); window.location.href='manage_movies.php';</script>";
    } else {
        echo "<script>alert('Error updating movie.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

<div class="admin-dashboard">
    <h1>Edit Movie</h1>
    <form method="POST">
        <input type="text" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
        <input type="text" name="poster" value="<?php echo htmlspecialchars($movie['poster']); ?>" required>
        <textarea name="description" required><?php echo htmlspecialchars($movie['description']); ?></textarea>
        <input type="date" name="release_date" value="<?php echo $movie['release_date']; ?>" required>
        <button type="submit">Update Movie</button>
    </form>
    <a href="manage_movies.php" class="dashboard-btn">Back</a>
</div>

</body>
</html>
