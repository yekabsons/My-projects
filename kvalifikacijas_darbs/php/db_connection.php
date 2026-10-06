<!-- Autors:Ervīns Jēkabsons; Datubāzes savienojuma konekcija
Veidots 2024.gadā, jaunākā vesrija-->
<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$servername = "localhost"; // Replace with your database server
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "car_registration";  // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set the charset to avoid character encoding issues
$conn->set_charset("utf8");

// Connection successful
// echo "Connected successfully"; // Uncomment for debugging purposes
?>
