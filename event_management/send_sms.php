<?php
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

// Assuming the current user's table is dynamically created based on their username
$current_user = 'sample_user'; // Replace with the actual logged-in user

// SQL to select event data from the user's dynamic table
$sql = "SELECT event_name, event_date, event_time, phone_number FROM `$current_user` WHERE event_date IS NOT NULL AND event_time IS NOT NULL";

// Run the query
$result = $conn->query($sql);

// Check if the query was successful
if ($result->num_rows > 0) {
    // Fetch event data and check if it's 1 hour away
    while ($row = $result->fetch_assoc()) {
        $eventName = $row['event_name'];
        $eventDate = $row['event_date'];
        $eventTime = $row['event_time'];
        $phoneNumber = $row['phone_number'];

        // Combine event date and time
        $eventDateTime = $eventDate . ' ' . $eventTime;

        // Calculate time difference between current time and event time
        $currentTime = new DateTime(); // Get the current time
        $eventDateTimeObj = new DateTime($eventDateTime); // Convert event date and time to DateTime object
        $interval = $currentTime->diff($eventDateTimeObj); // Get the time difference

        // Check if the event is 1 hour away
        if ($interval->h == 1 && $interval->d == 0) {
            // Prepare the message to send
            $message = "Reminder: Your event '$eventName' is coming up at $eventTime on $eventDate. Don't forget to attend!";

            // Call the Textbelt API to send the SMS
            $apiKey = "textbelt"; // Use your Textbelt API key
            $response = file_get_contents("https://textbelt.com/text?phone=$phoneNumber&message=" . urlencode($message) . "&key=$apiKey");

            // Decode the response
            $result = json_decode($response, true);

            // Check if SMS was sent successfully
            if ($result['success']) {
                echo "SMS sent successfully to $phoneNumber!";
            } else {
                echo "Failed to send SMS. Error: " . $result['error'];
            }
        }
    }
} else {
    echo "No events found for the user.";
}

// Close the database connection
$conn->close();
?>
