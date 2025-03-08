<?php
include 'db_connection.php';

// Set the time limit for unpaid bookings (10 minutes)
$cancel_time_limit = 10; // in minutes

// SQL to cancel unpaid bookings older than 10 minutes
$sql = "UPDATE bookings 
        SET booking_status = 'Cancelled' 
        WHERE booking_status = 'Pending' 
        AND TIMESTAMPDIFF(MINUTE, booked_at, NOW()) >= ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cancel_time_limit);

if ($stmt->execute()) {
    echo "Unpaid bookings older than 10 minutes have been cancelled.";
} else {
    echo "Error cancelling unpaid bookings.";
}

$conn->close();
?>
