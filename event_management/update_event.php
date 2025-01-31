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

// Fetch events for the logged-in user (from their dynamic table)
$sql = "SELECT event_name FROM `$current_user`";  // Get event names for the user
$result = $conn->query($sql);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row['event_name'];
    }
}

// Handle form submission for event update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_name']) && empty($_POST['updated_event_name'])) {
    $event_name = $_POST['event_name'];
    
    // Fetch the current details of the selected event
    $sql = "SELECT * FROM `$current_user` WHERE event_name='$event_name'";
    $event_result = $conn->query($sql);
    if ($event_result->num_rows > 0) {
        $event_details = $event_result->fetch_assoc();
    }
}

// Update the event if new details are provided
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updated_event_name']) && isset($_POST['updated_event_date']) && isset($_POST['updated_event_time']) && isset($_POST['updated_venue']) && isset($_POST['updated_phone_number'])) {
    $updated_event_name = $_POST['updated_event_name'];
    $updated_event_date = $_POST['updated_event_date'];
    $updated_event_time = $_POST['updated_event_time'];
    $updated_venue = $_POST['updated_venue'];
    $updated_phone_number = $_POST['updated_phone_number'];
    
    $event_name = $_POST['event_name']; // Original event name to find the event in the table

    // SQL query to update the event details
    $update_sql = "UPDATE `$current_user` 
                   SET event_name='$updated_event_name', event_date='$updated_event_date', event_time='$updated_event_time', venue='$updated_venue', phone_number='$updated_phone_number' 
                   WHERE event_name='$event_name'";

    if ($conn->query($update_sql) === TRUE) {
        // Redirect to dashboard after update
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating event: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Event</title>
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
    <h1>Update Event</h1>

    <?php if (empty($event_details)): ?>
        <!-- Event Selection Form -->
        <form action="update_event.php" method="POST">
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

            <input type="submit" value="Select Event">
        </form>
    <?php else: ?>
        <!-- Event Update Form -->
        <form action="update_event.php" method="POST">
            <input type="hidden" name="event_name" value="<?php echo $event_details['event_name']; ?>">

            <div class="form-group">
                <label for="updated_event_name">Event Name:</label>
                <input type="text" id="updated_event_name" name="updated_event_name" value="<?php echo $event_details['event_name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="updated_event_date">Event Date:</label>
                <input type="date" id="updated_event_date" name="updated_event_date" value="<?php echo $event_details['event_date']; ?>" required>
            </div>

            <div class="form-group">
                <label for="updated_event_time">Event Time:</label>
                <input type="time" id="updated_event_time" name="updated_event_time" value="<?php echo $event_details['event_time']; ?>" required>
            </div>

            <div class="form-group">
                <label for="updated_venue">Venue:</label>
                <input type="text" id="updated_venue" name="updated_venue" value="<?php echo $event_details['venue']; ?>" required>
            </div>

            <div class="form-group">
                <label for="updated_phone_number">Notifying Phone Number:</label>
                <input type="tel" id="updated_phone_number" name="updated_phone_number" value="<?php echo $event_details['phone_number']; ?>" required>
            </div>

            <input type="submit" value="Update Event">
        </form>
    <?php endif; ?>

    <div class="back-link">
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
