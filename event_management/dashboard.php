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
$sql = "SELECT id, event_name, venue, event_date, event_time, phone_number FROM `$current_user`"; 
$result = $conn->query($sql);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            background-image: url('dashboard-bg.jpg'); /* Background image */
            background-size: cover;
            background-position: center;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent black background */
            border-radius: 10px;
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .header h1 {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid white;
            text-align: left;
            padding: 10px;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .event-link {
            color: #ddd;
            text-align: center;
            display: block;
            margin-top: 10px;
        }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .delete-btn {
            background-color: red;
            margin-left: 10px;
        }

        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <p>Your Events:</p>
    </div>

    <table>
        <tr>
            <th>Event Name</th>
            <th>Venue</th>
            <th>Event Date</th>
            <th>Event Time</th>
            <th>Phone Number</th>
        </tr>
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?php echo $event['event_name']; ?></td>
                    <td><?php echo $event['venue']; ?></td>
                    <td><?php echo $event['event_date']; ?></td>
                    <td><?php echo $event['event_time']; ?></td>
                    <td><?php echo $event['phone_number']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No events available.</td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="event-link">
        <a href="add_event.php" class="btn">Add Event</a>
    </div>

    <div class="action-buttons">
        <a href="update_event.php" class="btn">Update Event</a>
        <a href="delete_event.php" class="btn delete-btn">Delete Event</a>
    </div>
</div>

</body>
</html>
