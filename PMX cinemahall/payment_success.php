<?php
session_start();
if (isset($_SESSION['status']) && $_SESSION['status'] == "Paid") {
    echo "<h2>Payment Successful!</h2>";
    echo "<p>Amount: " . $_SESSION['amount'] . "</p>";
    echo "<p>Payment Method: " . $_SESSION['payment_method'] . "</p>";
} else {
    header("Location: payment.php");
}
?>
