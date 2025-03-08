<?php
session_start();
require 'db_connection.php'; // Database connection

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$firstname = $isLoggedIn ? htmlspecialchars($_SESSION['firstname']) : '';

// Get tomorrow's date in `Y-m-d` format
$specific_date = date("Y-m-d", strtotime("+2 day"));

// Fetch movies scheduled for tomorrow
$sql = "SELECT * FROM movies WHERE release_date = '$specific_date'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>specific-date Movies</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div id="header">
        <div class="logo">
            <a href="Home.php">
                <img src="logo.png" alt="Logo">
                <span>Peak Cinemas</span>
            </a>
        </div>
        <div class="menu">
            <a href="Home.php" class="home">Home</a>
            <a href="showtimes.php">Show Times</a>
            <a href="about.php">About Us</a>
        </div>
        <div class="logreg">
            <?php if ($isLoggedIn): ?>
                <span>Hi, <?php echo $firstname; ?>!</span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php" class="login">Login</a>
                <a href="register.php" class="register">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- ✅ Slideshow Section (Fixed) -->
    <div class="slideshow-container">
        <div class="slide fade">
            <img src="moana.jpeg" alt="Movie 1">
        </div>
        <div class="slide fade">
            <img src="ends.jpg" alt="Movie 2">
        </div>
        <div class="slide fade">
            <img src="demon.jpg" alt="Movie 3">
        </div>
        <div class="slide fade">
            <img src="attackontaitan.jpg" alt="Movie 4">
        </div>
    </div>

    <script>
        let slideIndex = 0;
        showSlides();
        function showSlides() {
            const slides = document.querySelectorAll(".slide");
            slides.forEach((slide) => (slide.style.display = "none"));
            slideIndex++;
            if (slideIndex > slides.length) slideIndex = 1;
            slides[slideIndex - 1].style.display = "block";
            setTimeout(showSlides, 3000); // Change every 3 seconds
        }
    </script>

    <section class="now-showing">
        <h2>Now Showing</h2>
        <div class="date-navigation">
            <a href="home.php"><button id="today">TODAY</button></a>
            <button id="tommorrow" onclick="filterMovies(getDate(1))">TOMORROW</button>
           <a href="specific-date.php"><button id="specific-date"class="active"></button></a>
        </div>

        <script>
            function getDate(daysAhead) {
    const date = new Date();
    date.setDate(date.getDate() + daysAhead);
    return date.toISOString().split('T')[0];
}

// Set the button label correctly
document.getElementById('specific-date').textContent = getDate(2);

// ✅ Fix redirection to correct pages
function filterMovies(date, page) {
    window.location.href = page + "?date=" + date;
}

// Correct button actions
document.getElementById('tommorrow').onclick = function () {
    filterMovies(getDate(1), "tommorrow.php"); // Redirect to tommorrow.php
};

document.getElementById('specific-date').onclick = function () {
    filterMovies(getDate(2), "specific-date.php"); // Redirect to specific-date.php
};

        </script>


        <div class="movie-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="movie-card">';
                    echo '<img src="' . $row['poster'] . '" alt="' . htmlspecialchars($row['title']) . '" class="movie-poster">';
                    echo '<p>' . htmlspecialchars($row['title']) . '</p>';
                    echo '<div class="buy-ticket">';
                    echo '<button><a href="buy-ticket.php?id=' . $row['id'] . '" class="no-underline">Buy Ticket</a></button>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>No movies available for this date.</p>";
            }
            ?>
        </div>
    </section>
</body>
</html>
