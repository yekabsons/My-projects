<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, profile_picture, is_admin FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $profile_picture, $is_admin);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Correct password
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['profile_picture'] = $profile_picture;
            $_SESSION['is_admin'] = $is_admin;

            // Redirect to register_car.php
            header("Location: car_data.php");
            exit();
        } else {
            // Wrong password
            header("Location: ../html/login.html?status=wrongpassword");
            exit();
        }
    } else {
        // Username does not exist
        header("Location: ../html/login.html?status=nouser");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
