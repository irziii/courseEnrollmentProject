<?php
session_start(); // Start the session to store user data
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "db";
// Create a database connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = "";
$password = "";

$emailError = $passwordError = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from form submission
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Check if the user is an admin
    if ($email == "admin@gmail.com" && $password == "ad123") {
        // Redirect to admin.php
        header("Location: admin.php");
        exit();
    }

    // Retrieve user ID from the database based on email and password
    $query = "SELECT id FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userId = $row["id"];

        // Save user ID in session
        $_SESSION["user_id"] = $userId;

        // Redirect to student.php
        header("Location: student.php");
        exit();
    } else {
        $errorMessage = "Invalid email or password";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
        <div class="container form p-5">
            <h2 class="my-4 text-center">Login Form</h2>
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" class="form-control" required>
                    <span class="error"><?php echo $emailError; ?></span> <!-- Display email error message -->
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="<?php echo $password; ?>" class="form-control" required>
                    <span class="error"><?php echo $passwordError; ?></span> <!-- Display password error message -->
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <p class="mt-3" style="color: red;"><?php echo $errorMessage; ?></p> <!-- Display error message in red color -->
            <p>Not registered yet? <a href="registration.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
