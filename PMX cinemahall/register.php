<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration form</title>
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
        .registration-form {
            background-color:lightgray;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 500px;
        }
        .registration-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .registration-form input {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }
        .registration-form button {
            width: 90%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }
        .registration-form button:hover {
            background-color: #0056b3;
        }
        .registration-form .already a {
            color: #007bff;
            text-decoration: none;
        }
        .registration-form .already a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="registration-form">
        <h2>Register</h2>
        <form action="register.php" method="post">
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm-password" placeholder="Confirm-Password" required>
            <button type="submit">Register</button>
            <div class="already">
            <a href="login.php"> Already Registered? </a>
        </div>
        </form>
    </div>
</body>
</html>

<?php
// Include the database connection file
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    // Input validation
    if (empty($firstname) || empty($lastname) || empty($phone) || empty($email) || empty($password) || empty($confirm_password)) {
        die("All fields are required.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        die("Invalid phone number format. It must be 10 digits.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if email or phone already exists
    $check_query = "SELECT * FROM user_table WHERE email = ? OR phone = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Email or phone number already exists.");
    }

    // Insert data into the database
    $insert_query = "INSERT INTO user_table (firstname, lastname, phone, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssss", $firstname, $lastname, $phone, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful!";
        // Redirect to login page
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>