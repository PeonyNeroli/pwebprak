<?php
// Database connection variables
$servername = "localhost";
$username = "root";  // Change to your MySQL username
$password = "";      // Change to your MySQL password

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Setting up Campus Arena Database</h2>";

// Read SQL file content
$sql_file = file_get_contents('create_database.sql');

// Split SQL commands by semicolon
$commands = explode(';', $sql_file);

// Execute each command
foreach($commands as $command) {
    $command = trim($command);
    if (!empty($command)) {
        if ($conn->query($command)) {
            echo "Successfully executed: " . htmlspecialchars(substr($command, 0, 50)) . "...<br>";
        } else {
            echo "Error executing command: " . htmlspecialchars(substr($command, 0, 50)) . "...<br>";
            echo "Error details: " . $conn->error . "<br>";
        }
    }
}

// Close connection
$conn->close();

echo "<br>Database setup completed. <a href='create_account.php'>Go to registration page</a>";
?>