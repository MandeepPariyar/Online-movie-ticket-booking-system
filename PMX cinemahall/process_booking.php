<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $showtime_id = $_POST['showtime_id'];
    $selected_seats = explode(',', $_POST['selected_seats']);
    $price = $_POST['price'];
    $payment_gateway = $_POST['payment_gateway'];
    $total_price = count($selected_seats) * $price;

    if (empty($selected_seats)) {
        echo "<script>alert('Please select at least one seat!'); window.location.href='showtimes.php';</script>";
        exit();
    }

    foreach ($selected_seats as $seat) {
        $insert = "INSERT INTO bookings (user_id, showtime_id, seat_number, seats_booked, total_price, booking_status, payment_status) 
                   VALUES (?, ?, ?, ?, ?, 'Pending', 'Pending')";
        $stmt = $conn->prepare($insert);
        $seats_booked = implode(',', $selected_seats);
        $stmt->bind_param("iissd", $user_id, $showtime_id, $seat, $seats_booked, $total_price);
        $stmt->execute();
    }

    if ($payment_gateway == 'esewa') {
        header("Location: esewa_payment.php?amount=$total_price&user_id=$user_id");
        exit();
    } elseif ($payment_gateway == 'khalti') {
        header("Location: khalti_payment.php?amount=$total_price&user_id=$user_id");
        exit();
    }
}
?>
