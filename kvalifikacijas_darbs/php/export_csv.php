<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

// Set headers for the CSV file download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=cars_data.csv');

// Open the output stream
$output = fopen('php://output', 'w');

// Write the CSV headers
fputcsv($output, ['ID', 'Make', 'Model', 'Year', 'Color', 'Fuel', 'Gearbox', 'Registration Number', 'VIN', 'Description']);

// Fetch car data from the database
$query = "SELECT id, make, model, year, color, fuel, gearbox, registration_number, vin, description FROM cars";
$result = $conn->query($query);

// Write rows to the CSV file
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

// Close the output stream and the database connection
fclose($output);
$conn->close();
?>