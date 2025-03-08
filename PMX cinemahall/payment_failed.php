<?php
session_start();
if (isset($_SESSION['status']) && $_SESSION['status'] == "Pending") {
    echo "<h2>Payment Failed!</h2>";
    echo "<p>Please try again or choose another payment method.</p>";
} else {
    header("Location: payment.php");
}
?>
