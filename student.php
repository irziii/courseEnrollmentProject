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

$userId = $_SESSION["user_id"];
$userEmail = "";
$userName="";
// Retrieve user email from the database based on ID
$query = "SELECT email FROM users WHERE id = $userId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userEmail = $row["email"];
}

// Retrieve user name from the database based on ID
$query = "SELECT name FROM users WHERE id = $userId";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userName = $row["name"];
}

// Handle Enroll Now button action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["course_id"])) {
    $courseId = $_POST["course_id"];

    // Retrieve course name from the database based on course ID
    $courseQuery = "SELECT course_name FROM courses WHERE id = $courseId";
    $courseResult = $conn->query($courseQuery);

    if ($courseResult->num_rows > 0) {
        $courseRow = $courseResult->fetch_assoc();
        $courseName = $courseRow["course_name"];

        // Insert enrollment into the database
        $insertQuery = "INSERT INTO enrollments (user_id, course_id) VALUES ($userId, $courseId)";
        if ($conn->query($insertQuery) === TRUE) {
            // Enrollment successful
            // Reload the page to reflect the changes
            header("Location: student.php");
            exit();
        } else {
            // Error occurred during enrollment
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    }
}

// Retrieve enrolled courses for the user
$enrolledCoursesQuery = "SELECT course_id FROM enrollments WHERE user_id = $userId";
$enrolledCoursesResult = $conn->query($enrolledCoursesQuery);

$enrolledCourses = array();
if ($enrolledCoursesResult->num_rows > 0) {
    while ($row = $enrolledCoursesResult->fetch_assoc()) {
        $enrolledCourses[] = $row["course_id"];
    }
}

// Retrieve course names from the database
$courseNamesQuery = "SELECT id, course_name FROM courses";
$courseNamesResult = $conn->query($courseNamesQuery);

$courseNames = array();
if ($courseNamesResult->num_rows > 0) {
    while ($row = $courseNamesResult->fetch_assoc()) {
        $courseNames[$row["id"]] = $row["course_name"];
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        .sidebar {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .course-list {
            padding: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-light nav sticky-top">
        <h2 style="color:white">Welcome, <?php echo $userName; ?>!</h2>

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

    <div class="container-fluid">
        <div class="row">
        <div class="col-md-2 sidebar">
            <h2 class="my-4">Enrolled Courses</h2>
            <?php if (count($enrolledCourses) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($enrolledCourses as $courseId): ?>
                        <li class="list-group-item"><?php echo $courseNames[$courseId]; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No courses enrolled</p>
            <?php endif; ?>
        </div>
            <div class="col-md-10">
                <h2 class="my-4">Course List</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="course-list">
                        <div class="card" style="width: 18rem;">
                         <img class="card-img-top" src="m1.jpeg" alt="Card image cap"> </div>
                            <h5>1. Programming Fundamentals</h5>
                            <?php if (in_array(1, $enrolledCourses)): ?>
                                <button class="btn btn-secondary" disabled>Enrolled</button>
                            <?php else: ?>
                                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <input type="hidden" name="course_id" value="1">
                                    <button type="submit" class="btn btn-dark">Enroll Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <!-- Repeat the above code block for other courses -->
                        <div class="course-list">
                        <div class="card" style="width: 18rem;">
                         <img class="card-img-top" src="m2.jpeg" alt="Card image cap"> </div>
                        <h5>2. Cyber Security</h5>
                            <?php if (in_array(2, $enrolledCourses)): ?>
                                <button class="btn btn-secondary" disabled>Enrolled</button>
                            <?php else: ?>
                                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <input type="hidden" name="course_id" value="2">
                                    <button type="submit" class="btn btn-dark">Enroll Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <!-- Repeat the above code block for other courses -->
                        <div class="course-list">
                        <div class="card" style="width: 18rem;">
                         <img class="card-img-top" src="m3.jpeg" alt="Card image cap"> </div>
                        <h5>3. Database</h5>
                            <?php if (in_array(3, $enrolledCourses)): ?>
                                <button class="btn btn-secondary" disabled>Enrolled</button>
                            <?php else: ?>
                                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <input type="hidden" name="course_id" value="3">
                                    <button type="submit" class="btn btn-dark">Enroll Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <!-- Repeat the above code block for other courses -->
                        <div class="course-list">
                        <div class="card" style="width: 18rem;">
                         <img class="card-img-top" src="m4.jpeg" alt="Card image cap"> </div>
                        <h5>4. Object Oriented Programming</h5>
                            <?php if (in_array(4, $enrolledCourses)): ?>
                                <button class="btn btn-secondary" disabled>Enrolled</button>
                            <?php else: ?>
                                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <input type="hidden" name="course_id" value="4">
                                    <button type="submit" class="btn btn-dark">Enroll Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <!-- Repeat the above code block for other courses -->
                        <div class="course-list">
                        <div class="card" style="width: 18rem;">
                         <img class="card-img-top" src="m5.png" alt="Card image cap"> </div>
                        <h5>5. Data Structure</h5>
                            <?php if (in_array(5, $enrolledCourses)): ?>
                                <button class="btn btn-secondary" disabled>Enrolled</button>
                            <?php else: ?>
                                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <input type="hidden" name="course_id" value="5">
                                    <button type="submit" class="btn btn-dark">Enroll Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="course-list">
                        <div class="card" style="width: 18rem;">
                         <img class="card-img-top" src="m6.jpg" alt="Card image cap"> </div>
                        <h5>6. Computer Architecture</h5>
                            <?php if (in_array(6, $enrolledCourses)): ?>
                                <button class="btn btn-secondary" disabled>Enrolled</button>
                            <?php else: ?>
                                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <input type="hidden" name="course_id" value="6">
                                    <button type="submit" class="btn btn-dark">Enroll Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <!-- Repeat the above code block for other courses -->
                        <div class="course-list">
                        <div class="card" style="width: 18rem;">
                         <img class="card-img-top" src="m7.jpg" alt="Card image cap"> </div>
                        <h5>7. Web Engineering</h5>
                            <?php if (in_array(7, $enrolledCourses)): ?>
                                <button class="btn btn-secondary" disabled>Enrolled</button>
                            <?php else: ?>
                                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <input type="hidden" name="course_id" value="7">
                                    <button type="submit" class="btn btn-dark">Enroll Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <!-- Repeat the above code block for other courses -->
                        <div class="course-list">
                        <div class="card" style="width: 18rem;">
                         <img class="card-img-top" src="m8.jpeg" alt="Card image cap"> </div>
                        <h5>8. Intro to Computer</h5>
                            <?php if (in_array(8, $enrolledCourses)): ?>
                                <button class="btn btn-secondary" disabled>Enrolled</button>
                            <?php else: ?>
                                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <input type="hidden" name="course_id" value="8">
                                    <button type="submit" class="btn btn-dark">Enroll Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <!-- Repeat the above code block for other courses -->
                        <div class="course-list">
                        <div class="card" style="width: 18rem;">
                         <img class="card-img-top" src="m9.webp" alt="Card image cap"> </div>
                        <h5>9. Computer Networks</h5>
                            <?php if (in_array(9, $enrolledCourses)): ?>
                                <button class="btn btn-secondary" disabled>Enrolled</button>
                            <?php else: ?>
                                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <input type="hidden" name="course_id" value="9">
                                    <button type="submit" class="btn btn-dark">Enroll Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <!-- Repeat the above code block for other courses -->
                        <div class="course-list">
                        <div class="card" style="width: 18rem;">
                         <img class="card-img-top" src="m10.png" alt="Card image cap"> </div>
                        <h5>10. Software Engineering</h5>
                            <?php if (in_array(10, $enrolledCourses)): ?>
                                <button class="btn btn-secondary" disabled>Enrolled</button>
                            <?php else: ?>
                                <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                                    <input type="hidden" name="course_id" value="10">
                                    <button type="submit" class="btn btn-dark">Enroll Now</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
