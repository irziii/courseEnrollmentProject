<?php 
session_start();
// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    // User is not logged in, redirect to login.php
    header("Location: login.php");
    exit();
}
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    
    <title>Document</title>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-light nav sticky-top">
        <h2 style="color:white">Welcome, Admin!</h2>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="login.php" class="btn btn-primary">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
<br>
    <div class="container p-3">
        <table class="table adtb">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Course Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //  database connection established
                $query = "SELECT enrollments.id, users.id AS student_id, users.name AS student_name, courses.course_name AS course_name FROM enrollments
                          INNER JOIN users ON enrollments.user_id = users.id
                          INNER JOIN courses ON enrollments.course_id = courses.id";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    $count = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<th scope='row'>" . $count . "</th>";
                        echo "<td>" . $row['student_id'] . "</td>";
                        echo "<td>" . $row['student_name'] . "</td>";
                        echo "<td>" . $row['course_name'] . "</td>";
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='4'>No data found</td></tr>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
