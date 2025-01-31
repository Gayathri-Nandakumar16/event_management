<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user's username
$current_user = $_SESSION['username'];

// Sanitize the username to avoid SQL injection risks
$current_user = mysqli_real_escape_string($conn, $current_user);

// Insert event into the user's dynamic table
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    
    // SQL query to insert the event data into the user's specific table
    $sql = "INSERT INTO `$current_user` (event_name, event_date, event_time, venue, phone_number) 
            VALUES ('$event_name', '$event_date', '$event_time', '$venue', '$phone_number')";
    
    if ($conn->query($sql) === TRUE) {
        // Event added successfully, redirect to dashboard
        header("Location: dashboard.php");  // Redirect to dashboard
        exit();  // Make sure no further code is executed
    } else {
        echo "Error adding event: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <style>
        body {
            background-image: url('dashboard-bg.jpg'); /* Background image */
            background-size: cover;
            background-position: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            color: white;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent black background */
            padding: 30px;
            border-radius: 10px;
            margin-top: 100px;
        }

        .container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-size: 18px;
        }

        input[type="text"], input[type="date"], input[type="time"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 5px;
            color: white;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
        }

        .back-link a {
            color: white;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Add Event</h1>
    <form action="add_event.php" method="POST">
        <div class="form-group">
            <label for="event_name">Event Name:</label>
            <input type="text" id="event_name" name="event_name" required>
        </div>

        <div class="form-group">
            <label for="event_date">Event Date:</label>
            <input type="date" id="event_date" name="event_date" required>
        </div>

        <div class="form-group">
            <label for="event_time">Event Time:</label>
            <input type="time" id="event_time" name="event_time" required>
        </div>

        <div class="form-group">
            <label for="venue">Venue:</label>
            <input type="text" id="venue" name="venue" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Notifying Phone Number:</label>
            <input type="tel" id="phone_number" name="phone_number" required>
        </div>

        <input type="submit" value="Submit">
    </form>

    <div class="back-link">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
