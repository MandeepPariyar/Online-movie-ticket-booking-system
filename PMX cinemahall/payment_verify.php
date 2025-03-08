<?php
include 'db_connection.php';

if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $user_id = $_GET['user_id'];
    $update = "UPDATE bookings SET booking_status='Confirmed', payment_status='Paid' WHERE user_id=?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    echo "<script>alert('Payment Successful! Booking Confirmed'); window.location.href='profile.php';</script>";
} else {
    echo "<script>alert('Payment Failed! Try Again'); window.location.href='profile.php';</script>";
}
?>
