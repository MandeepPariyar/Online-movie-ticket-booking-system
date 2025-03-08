<?php
session_start();
include '../db_connection.php'; // Correct path to database connection

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

$sql = "SELECT id, user_id, amount, method, status, date FROM payment";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h2>Payment History</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Amount</th>
        <th>Method</th>
        <th>Status</th>
        <th>Date</th>
        <th>Action</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['user_id']; ?></td>
            <td><?= $row['amount']; ?></td>
            <td><?= $row['method']; ?></td>
            <td><?= $row['status']; ?></td>
            <td><?= $row['date']; ?></td>
            <td>
                <?php if ($row['status'] == "Pending") { ?>
                    <a href="verify_payment.php?id=<?= $row['id']; ?>">Verify</a>
                <?php } else { ?>
                    Verified
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
