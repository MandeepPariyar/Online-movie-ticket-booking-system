<?php
session_start();
include 'db_connection.php';

// Fetch movie showtimes from the database
$sql = "SELECT movies.id AS movie_id, movies.title, movies.poster, showtimes.id AS showtime_id, showtimes.show_time 
        FROM movies 
        JOIN showtimes ON movies.id = showtimes.movie_id 
        ORDER BY showtimes.show_time ASC";
$result = $conn->query($sql);

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$firstname = $isLoggedIn ? htmlspecialchars($_SESSION['firstname']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showtimes Page</title>
    <link rel="stylesheet" href="showtime_style.css">
    <link rel="stylesheet" href="home.css">
</head>
<body>

    <!-- Header -->
    <div id="header">
        <div class="logo">
            <a href="home.php">
                <img src="logo.png" alt="Logo">
                <span>Peak Cinemas</span>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="menu">
            <a href="home.php">Home</a>
            <a href="showtimes.php" class="showtimes">Show Times</a>
            <a href="about.php">About Us</a>
        </div>

        <div class="logreg">
            <?php if ($isLoggedIn): ?>
                <span>Hi, <?php echo $firstname; ?>!</span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Showtime Section -->
    <section class="showtime-section">
        <h2>Movie Showtimes</h2>

        <div class="showtime-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="movie-card">
                        <img src="<?php echo $row['poster']; ?>" alt="<?php echo $row['title']; ?>" class="movie-poster">
                        <h3><?php echo $row['title']; ?></h3>
                        <p>Showtime: <?php echo date("h:i A", strtotime($row['show_time'])); ?></p>
                        
                        <!-- Buy Ticket Button with both movie_id and showtime_id -->
                        <a href="buy-ticket.php?movie_id=<?php echo $row['movie_id']; ?>&showtime_id=<?php echo $row['showtime_id']; ?>" class="buy-btn">Buy Ticket</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No showtimes available.</p>
            <?php endif; ?>
        </div>
    </section>

</body>
</html>
