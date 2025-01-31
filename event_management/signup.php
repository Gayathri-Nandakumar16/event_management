<?php
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Create user table dynamically
    $create_table = "CREATE TABLE IF NOT EXISTS `$username` (
                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        event_name VARCHAR(255) NOT NULL,
                        event_date DATE NOT NULL,
                        event_time TIME NOT NULL,
                        venue VARCHAR(255) NOT NULL,
                        phone_number VARCHAR(20) NOT NULL
                    )";

    if ($conn->query($create_table) === TRUE) {
        // Insert user details into the users table
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "Account created successfully. <a href='login.html'>Login</a>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error creating table: " . $conn->error;
    }
}
$conn->close();
?>
