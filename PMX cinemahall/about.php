<?php
session_start(); // Start the session
include 'db_connection.php'; // Include your database connection

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$firstname = $isLoggedIn ? htmlspecialchars($_SESSION['firstname']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="about_style.css">
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
            <a href="home.php">Home</a>
            <a href="showtimes.php">Show Times</a>
            <a href="about.php" class="about">About Us</a>
        </div>

        <!-- Login/Register or User Greeting -->
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

    <main>
        <section class="about-us">
            <h1>About Us</h1>
            <p>Welcome to Peak Cinemas, your ultimate destination for an unforgettable cinematic experience. Our mission is to bring the magic of movies closer to you with state-of-the-art technology, exceptional service, and a passion for storytelling.</p>
            
            <h2>Our Vision</h2>
            <p>To become Nepal’s leading destination for movie lovers, providing an inclusive and enjoyable ticketing environment for everyone.</p>
            
            <h2>Features</h2>
            <ul>
                <li>Crystal-clear visuals with advanced projection technology</li>
                <li>Immersive surround sound</li>
                <li>Easy online ticket booking</li>
            </ul>
            
            <h2>Contact Us</h2>
            <p>Visit us at our cinema or get in touch:</p>
            <p><strong>Address:</strong> Sinamangal </p>
            <p><strong>Phone:</strong> +977-9766909144</p>
            
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Peak Cinemas. All rights reserved.</p>
    </footer>

</body>
</html>
