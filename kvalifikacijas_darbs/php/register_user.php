<?php
// Autors: Ervīns Jēkabsons
// Koda daļa, kas apstrādā reģistrācijas pieprasījumu un saglabā datus datubāzē

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
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $default_role = 'user';

    // Check if email exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        header("Location: ../html/register_user.html?status=email_exists&clear=email");
        exit();
    }
    $stmt->close();

    // Check if username exists
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        header("Location: ../html/register_user.html?status=username_exists");
        exit();
    }
    $stmt->close();

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $default_role);

    if ($stmt->execute()) {
        header("Location: ../html/login.html?status=registered");
        exit();
    } else {
        header("Location: ../html/register_user.html?status=error");
        exit();
    }

    $stmt->close();
}
$conn->close();
?>