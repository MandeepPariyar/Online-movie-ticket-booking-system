<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['movie_id']) || !isset($_GET['showtime_id'])) {
    echo "<script>alert('Invalid request. Please select a movie.'); window.location.href='showtimes.php';</script>";
    exit();
}

$movie_id = $_GET['movie_id'];
$showtime_id = $_GET['showtime_id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT movies.title, movies.poster, showtimes.show_time, showtimes.price 
        FROM movies 
        JOIN showtimes ON movies.id = showtimes.movie_id 
        WHERE movies.id = ? AND showtimes.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $movie_id, $showtime_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Invalid movie or showtime.'); window.location.href='showtimes.php';</script>";
    exit();
}

$movie = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Selection</title>
    <link rel="stylesheet" href="home.css">
    <style>
        .seat-container {
            display: grid;
            grid-template-columns: repeat(13, 1fr);
            gap: 10px;
            margin: 30px 0;
            text-align: center;
        }
        .seat {
            width: 40px;
            height: 40px;
            background: yellow;
            border: 1px solid black;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
        }
        .seat.selected {
            background: red;
            color: white;
        }
        .seat.occupied {
            background: darkred;
            cursor: not-allowed;
        }
        .screen {
            background: red;
            color: white;
            text-align: center;
            margin: 20px auto;
            padding: 10px;
            border-radius: 10px;
            width: 300px;
        }
    </style>
</head>
<body>

<div id="header">
    <div class="logo">
        <a href="home.php">
            <img src="logo.png" alt="Logo">
            <span>Peak Cinemas</span>
        </a>
    </div>
    <div class="menu">
        <a href="home.php">Home</a>
        <a href="showtimes.php">Show Times</a>
        <a href="about.php">About Us</a>
    </div>
    <div class="logreg">
        <span>Hi, <?php echo htmlspecialchars($_SESSION['firstname']); ?>!</span>
        <a href="logout.php">Logout</a>
    </div>
</div>

<section class="booking-section">
    <h2>Book Your Seat</h2>
    <div class="movie-details">
        <img src="<?php echo $movie['poster']; ?>" alt="<?php echo $movie['title']; ?>">
        <div class="movie-info">
            <h3><?php echo $movie['title']; ?></h3>
            <p>Showtime: <?php echo date("h:i A", strtotime($movie['show_time'])); ?></p>
            <p>Price per ticket: Rs. <?php echo number_format($movie['price'], 2); ?></p>
        </div>
    </div>

    <div class="screen">Screen This Side</div>

    <form action="process_booking.php" method="POST">
        <div class="seat-container">
            <?php
            $rows = ['A', 'B', 'C', 'D'];
            $cols = 13;

            foreach ($rows as $row) {
                for ($i = 1; $i <= $cols; $i++) {
                    $seat = $row . sprintf("%02d", $i);

                    $check = "SELECT * FROM bookings WHERE showtime_id = ? AND seat_number = ?";
                    $stmt = $conn->prepare($check);
                    $stmt->bind_param("is", $showtime_id, $seat);
                    $stmt->execute();
                    $seatResult = $stmt->get_result();

                    $class = $seatResult->num_rows > 0 ? 'occupied' : '';
                    echo "<div class='seat $class' data-seat='$seat'>$seat</div>";
                }
            }
            ?>
        </div>

        <input type="hidden" name="selected_seats" id="selected_seats">
        <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
        <input type="hidden" name="showtime_id" value="<?php echo $showtime_id; ?>">
        <input type="hidden" name="price" value="<?php echo $movie['price']; ?>">

        <label>Select Payment Gateway:</label>
        <select name="payment_gateway" required>
            <option value="fake_esewa">Fake eSewa</option>
            <option value="fake_khalti">Fake Khalti</option>
        </select>

        <button type="submit">Proceed to Payment</button>
    </form>
</section>

<script>
    const seats = document.querySelectorAll('.seat:not(.occupied)');
    const selectedSeats = document.getElementById('selected_seats');
    let selected = [];
    let price = <?php echo $movie['price']; ?>;

    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            if (!seat.classList.contains('selected')) {
                seat.classList.add('selected');
                selected.push(seat.dataset.seat);
            } else {
                seat.classList.remove('selected');
                selected = selected.filter(s => s !== seat.dataset.seat);
            }
            selectedSeats.value = selected.join(',');
        });
    });
</script>

</body>
</html>
