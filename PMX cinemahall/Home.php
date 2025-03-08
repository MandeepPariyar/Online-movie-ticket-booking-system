
<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$firstname = $isLoggedIn ? htmlspecialchars($_SESSION['firstname']) : '';

// Fetch movies for the slideshow (Latest 5 movies)
$slideshow_sql = "SELECT poster, title FROM movies ORDER BY id DESC LIMIT 5";
$slideshow_result = $conn->query($slideshow_sql);

// Fetch movies for "Now Showing" based on today's date
$today_date = date('Y-m-d');
$now_showing_sql = "SELECT * FROM movies WHERE release_date = '$today_date' ORDER BY id DESC";
$now_showing_result = $conn->query($now_showing_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>

  <link rel="stylesheet" href="home.css">
  
  
</head>
<body>

    <div id="header">
        <div class="logo">
            <a href="home.php">
                <img src="logo.png" alt="Logo">
                <span>Peak Cinemas</span>
            </a>
        </div>

        <!-- Navigation Menu -->
        <div class="menu">
            <a href="home.php" class="home">Home</a>
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

    <!-- 🎥 Dynamic Slideshow -->
    <div class="slideshow-container">
        <?php if ($slideshow_result->num_rows > 0): ?>
            <?php while ($slide = $slideshow_result->fetch_assoc()): ?>
                <div class="slide fade">
                    <img src="<?php echo $slide['poster']; ?>" alt="<?php echo htmlspecialchars($slide['title']); ?>">
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No movies available in the slideshow.</p>
        <?php endif; ?>
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

    <!-- Now Showing Section -->
    <section class="now-showing">
        <h2>Now Showing</h2>
        <div class="date-navigation">
            <button id="today" class="active" onclick="filterMovies('<?php echo $today_date; ?>')">TODAY</button>
            <button id="tommorrow" onclick="filterMovies(getDate(1))">TOMORROW</button>
            <button id="specific-date" onclick="filterMovies(getDate(2))"></button>
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
            <?php if ($now_showing_result->num_rows > 0): ?>
                <?php while ($row = $now_showing_result->fetch_assoc()): ?>
                    <div class="movie-card">
                        <img src="<?php echo $row['poster']; ?>" alt="<?php echo $row['title']; ?>" class="movie-poster">
                        <p><?php echo $row['title']; ?></p>
                        <div class="buy-ticket">
                            <button onclick="checkLogin(<?php echo $row['id']; ?>)">Buy Ticket</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No movies available for this date.</p>
            <?php endif; ?>
        </div>
    </section>

    <script>
        function checkLogin(movieId) {
            const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
            
            if (isLoggedIn) {
                window.location.href = "buy-ticket.php?movie_id=" + movieId; // Redirect with movie_id
            } else {
                window.location.href = "login.php"; // Redirect to login page if not logged in
            }
        }
    </script>
    <footer>
    <p>© 2025 Peak Cinemas | All Rights Reserved</p>
</footer>
</body>
</html>




