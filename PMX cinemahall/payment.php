<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Movie Ticket Payment</h2>
<form action="payment_process.php" method="POST">
    <label>Payment Method:</label>
    <select name="payment_method" required>
        <option value="">Select Payment Method</option>
        <option value="Esewa">Esewa</option>
        <option value="Khalti">Khalti</option>
        <option value="Cash">Cash On Delivery</option>
    </select>
    
    <input type="hidden" name="amount" value="200">
    <button type="submit" name="pay_now">Pay Now</button>
</form>
</body>
</html>
