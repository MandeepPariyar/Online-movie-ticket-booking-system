<?php
session_start(); // Start the session
include 'db_connection.php';

$error = ""; // Initialize error message

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Query to fetch user data
    $sql = "SELECT * FROM user_table WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['lastname'] = $row['lastname'];
            $_SESSION['email'] = $row['email'];
            
            // Redirect to home page
            header("Location: Home.php");
            exit();
        } else {
            // Incorrect password
            $error = "Incorrect password!";
        }
    } else {
        // User not found
        $error = "User not found!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rosybrown;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-form {
            background-color:lightgrey;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px;
        }
        .login-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .login-form input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }
        .login-form button {
            width: 90%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .login-form button:hover {
            background-color: #218838;
        }
        .login-form .forgot-password {
            text-align: center;
            margin-top: 10px;
        }
        .login-form .forgot-password a {
            color: #007bff;
            text-decoration: none;
        }
        .login-form .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div style="color: red; text-align: center;"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="forgot-password">
            <a href="register.php">Do not have an account?</a>
        </div>
    </div>
</body>
</html>
