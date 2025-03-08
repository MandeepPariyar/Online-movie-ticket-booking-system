<?php
session_start();
include 'db.php'; // Database Connection File

if (isset($_POST['pay_now'])) {
    $method = $_POST['payment_method'];
    $amount = $_POST['amount'];
    $user_id = $_SESSION['user_id']; // Assuming user is logged in
    $status = "";

    if ($method == "Cash") {
        $status = "Pending";
    } else {
        $status = "Paid";
    }

    // Save Payment in Database
    $sql = "INSERT INTO payment (user_id, amount, method, status) VALUES ('$user_id', '$amount', '$method', '$status')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION['status'] = $status;

        if ($status == "Paid") {
            header("Location: payment_success.php");
        } else {
            header("Location: payment_failed.php");
        }
    } else {
        echo "Payment Failed!";
    }
}
?>
