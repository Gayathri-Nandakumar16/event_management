<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // Bind the username parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['username'] = $username; // Set session variable
            header("Location: dashboard.php"); // Redirect to dashboard
            exit(); // Stop script execution after redirection
        } else {
            echo "Invalid password. Please <a href='login.html'>try again</a>.";
        }
    } else {
        echo "User not found. Please <a href='signup.html'>sign up</a>.";
    }

    $stmt->close(); // Close the statement
}

$conn->close();
?>
