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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_name'])) {
    $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
    
    // SQL query to delete the selected event
    $sql = "DELETE FROM `$current_user` WHERE event_name = '$event_name'";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to the dashboard if event is deleted
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error deleting event: " . $conn->error;
    }
}

// Fetch events for the logged-in user (from their dynamic table)
$sql = "SELECT event_name FROM `$current_user`";  // Get event names for the user
$result = $conn->query($sql);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row['event_name'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event</title>
    <style>
        body {
            background-image: url('dashboard-bg.jpg');
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
            background: rgba(0, 0, 0, 0.7);
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

        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent white background */
            border: 1px solid #ccc; /* Add border for better visibility */
            border-radius: 5px;
            color: black; /* Set text color inside the dropdown to black */
        }

        option {
            background-color: white; /* Set background color for options */
            color: black; /* Set text color inside the options to black */
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #f44336;
            font-size: 18px;
            cursor: pointer;
            color: white;
            border-radius: 5px;
            border: none;
        }

        input[type="submit"]:hover {
            background-color: #e53935;
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
    <h1>Delete Event</h1>
    <form action="delete_event.php" method="POST" onsubmit="return confirmDelete()">
        <div class="form-group">
            <label for="event_name">Select Event:</label>
            <select id="event_name" name="event_name" required>
                <option value="">--Select an Event--</option>
                <?php
                if (count($events) > 0) {
                    foreach ($events as $event) {
                        echo "<option value='$event'>$event</option>";
                    }
                } else {
                    echo "<option value='' disabled>No events available</option>";
                }
                ?>
            </select>
        </div>
        
        <input type="submit" value="Delete Event">
    </form>

    <div class="back-link">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</div>

<script>
    // JavaScript function to confirm the deletion
    function confirmDelete() {
        return confirm("Are you sure you want to permanently delete this event?");
    }
</script>

</body>
</html>
