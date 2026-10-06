<!-- Autors:Ervīns Jēkabsons; Dzēšanas funkcija pieeja administratoram kurš spēj dzēst dažādus datus
Veidots 2024.gadā, jaunākā vesrija-->
<?php
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
            alert('Access Denied! Only admins can perform this action.');
            window.location.href = 'car_data.php'; // Redirect to login page or any other page
          </script>";
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if car ID is provided
if (isset($_GET['id'])) {
    $carId = intval($_GET['id']); // Ensure it's an integer

    // Fetch the photo paths associated with the car
    $stmt = $conn->prepare("SELECT photo FROM cars WHERE id = ?");
    $stmt->bind_param("i", $carId);
    $stmt->execute();
    $stmt->bind_result($photoJson);
    $stmt->fetch();
    $stmt->close();

    // Delete associated photos from the server
    $photos = json_decode($photoJson, true); // Decode JSON string to array
    if (is_array($photos)) {
        foreach ($photos as $photo) {
            if (file_exists($photo)) {
                unlink($photo); // Delete the file
            }
        }
    }

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->bind_param("i", $carId);

    if ($stmt->execute()) {
        // Redirect back to the main page after deletion
        header("Location: car_data.php?success=Car deleted successfully");
        exit;
    } else {
        echo "Error deleting car: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "No car ID provided.";
}

$conn->close();
?>
