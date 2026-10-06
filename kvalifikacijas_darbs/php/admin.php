<!-- Autors:Ervīns Jēkabsons; Šī lapa ir paredzēta tikai administratoram,
kurš spēj pāredzēt visu kas notiek šajā mājas lapā un pārvaldi't lietotāja ievadītos datus
Veidots 2024.gadā, jaunākā vesrija-->
<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
            alert('Access Denied! Only admins can access this page.');
            window.location.href = 'login.php';
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

// Fetch all users
$usersQuery = "SELECT id, username, email, role FROM users";
$usersResult = $conn->query($usersQuery);

// Fetch all cars
$carsQuery = "SELECT c.id, c.make, c.model, c.photo, u.username AS owner
              FROM cars c
              LEFT JOIN users u ON c.user_id = u.id";
$carsResult = $conn->query($carsQuery);

// Handle delete actions
if (isset($_GET['car_id']) || isset($_GET['user_id'])) {
    $carId = filter_var($_GET['car_id'], FILTER_VALIDATE_INT);
    $userId = filter_var($_GET['user_id'], FILTER_VALIDATE_INT);

    if ($carId) {
        // Delete car
        $stmt = $conn->prepare("DELETE FROM cars WHERE id = ?");
        $stmt->bind_param("i", $carId);
        if ($stmt->execute()) {
            header("Location: admin.php?success=Car deleted successfully");
            exit;
        } else {
            echo "<script>alert('Failed to delete the car.');</script>";
        }
        $stmt->close();
    }

    if ($userId) {
        // Delete user
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            header("Location: admin.php?success=User deleted successfully");
            exit;
        } else {
            echo "<script>alert('Failed to delete the user.');</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" href="https://www.shutterstock.com/image-vector/automobile-check-mark-icon-vector-600nw-1414290941.jpg">
    <script type="text/javascript">
        // JavaScript function to confirm deletion
        function confirmDelete(message) {
            return confirm(message);
        }
    </script>
</head>
<body>
<div class="admin-container">
    <div class="admin-header">
        <div class="logout-container">
            <a href="logout.php"><button>Logout</button></a>
            <button onclick="window.location.href='export_csv.php'">Export to excel</button>
        </div>
        <center>
        <h2>Admin Dashboard</h2>
        </center>
    </div>

    <div class="main-content">
        <?php if (isset($_GET['success'])): ?>
            <p class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php endif; ?>

        <h2>All Users</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($usersResult && $usersResult->num_rows > 0): ?>
                <?php while ($user = $usersResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <?php if ($user['role'] !== 'admin'): ?>
                                <a href="admin.php?user_id=<?php echo $user['id']; ?>" onclick="return confirmDelete('Are you sure you want to delete this user?');">Delete</a>
                            <?php else: ?>
                                Admin cannot be deleted.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No users found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <h2>All Cars</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Car Make</th>
                <th>Car Model</th>
                <th>Photos</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($carsResult->num_rows > 0): ?>
                <?php while ($car = $carsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $car['id']; ?></td>
                        <td><?php echo $car['make']; ?></td>
                        <td><?php echo $car['model']; ?></td>
                        <td>
                            <?php
                            $photos = json_decode($car['photo'], true); // Decode the JSON-encoded photo paths
                            if (is_array($photos) && !empty($photos)) {
                                foreach ($photos as $photo) {
                                    echo "<img src='" . htmlspecialchars($photo, ENT_QUOTES) . "' style='width:80px; height:auto; margin-right:5px;'>";
                                }
                            } else {
                                echo "No images available";
                            }
                            ?>
                        </td>
                        <td>
                            <!-- Delete confirmation for cars -->
                            <a href="admin.php?car_id=<?php echo $car['id']; ?>" onclick="return confirmDelete('Are you sure you want to delete this car?');">Delete Car</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No cars found</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
<style>
    /* Root Variables */
    :root {
        --primary-color: #000000;
        --secondary-color: #ffffff;
        --accent-color: #e74c3c;
        --text-color: #333;
        --background-color: #f4f4f9;
        --card-bg: white;
        --header-bg: #2c3e50;
        --table-hover: #f9f9f9;
    }

    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: var(--background-color);
        color: var(--text-color);
        line-height: 1.6;
    }

    /* Admin Header */
    .admin-header {
        display: flow;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
        background-color: var(--header-bg);
        color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .admin-header h2 {
        font-size: 1.8rem;
    }

    /* Adjust logout button position */
    .logout-container {
        position: absolute;
        left: 2rem; /* Adjust left spacing if needed */
        top: 1rem;  /* Adjust top spacing if needed */
    }

    .logout-container button {
        background-color: var(--accent-color);
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .logout-container button:hover {
        background-color: #c0392b;
        transform: scale(1.05);
    }

    /* Main Content */
    .main-content {
        padding: 2rem;
        margin-top: 1rem;
    }

    .main-content h2 {
        color: var(--secondary-color);
        margin-bottom: 1rem;
    }

    /* Success Message */
    .success-message {
        color: green;
        margin-bottom: 1rem;
    }

    /* Tables */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
        background-color: var(--card-bg);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    table thead {
        background-color: var(--secondary-color);
        color: white;
    }

    table th, table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table tbody tr:nth-child(odd) {
        background-color: var(--background-color);
    }

    table tbody tr:hover {
        background-color: var(--table-hover);
    }

    a {
        text-decoration: none;
        color: var(--primary-color);
        transition: color 0.3s ease, text-shadow 0.3s ease;
    }

    a:hover {
        color: var(--accent-color);
        text-shadow: 0px 0px 5px var(--accent-color);
    }

    /* Image Styling */
    img {
        width: 80px;
        height: auto;
        margin-right: 5px;
        border-radius: 5px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        table th, table td {
            font-size: 0.8rem;
            padding: 0.5rem;
        }
    }
</style>
</html>

<?php
$conn->close();
?>
