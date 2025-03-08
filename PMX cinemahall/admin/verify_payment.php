<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE payment SET status='Paid' WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: payment_history.php");
}
?>
