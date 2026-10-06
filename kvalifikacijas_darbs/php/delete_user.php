<!-- Autors:Ervīns Jēkabsons; Šajā koda daļā var dzēst lietotājus, to var darīt tikai administrators
Veidots 2024.gadā, jaunākā vesrija-->
<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access Denied! Only admins can perform this action.";
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

// Check if a user ID is provided
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Ensure the admin cannot delete themselves
    if ($user_id == $_SESSION['user_id']) {
        echo "<script>alert('You cannot delete your own account.');</script>";
        echo "<script>window.location.href = 'admin.php';</script>";
        exit;
    }

    // Delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('User has been successfully deleted.');</script>";
        echo "<script>window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Error occurred while deleting the user. Please try again.');</script>";
        echo "<script>window.location.href = 'admin.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('No user ID provided.');</script>";
    echo "<script>window.location.href = 'admin.php';</script>";
}

$conn->close();
?>
