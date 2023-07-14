<?php
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

// Define variables and initialize with empty values
$name = $email = $password = "";
$nameError = $emailError = $passwordError = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $name = trim($_POST["name"]);
    if (empty($name)) {
        $nameError = "Name is required";
    }

    // Validate email
    $email = trim($_POST["email"]);
    if (empty($email)) {
        $emailError = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format";
    } else {
        // Check if email already exists in the database
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $emailError = "Email already exists";
        }
    }

    // Validate password
    $password = trim($_POST["password"]);
    if (empty($password)) {
        $passwordError = "Password is required";
    }
    // If all fields are valid, insert new user into the database
    if (empty($nameError) && empty($emailError) && empty($passwordError)) {
        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

        if ($conn->query($query) === TRUE) {
            $successMessage = "Registration successful";
            $name = $email = $password = "";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
<div class="d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="container form p-4">
        <h2 class="my-4 text-center">Registration Form</h2>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="form-control" required>
                <span class="error"><?php echo $nameError; ?></span> <!-- Display name error message -->
            </div>
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
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p class="mt-3 alert alert-success"><?php echo $successMessage; ?></p> <!-- Display success message -->
        <p>Want to login? <a href="login.php">Login here</a></p>
    </div>
</div>
</body>
</html>
