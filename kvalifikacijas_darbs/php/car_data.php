<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "car_registration");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM cars WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Registered Cars</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .car-card img {
            height: 200px;
            object-fit: cover;
        }
        .car-visual {
            width: 120px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 0.75rem;
            margin: 0 auto 1rem auto;
        }
    </style>
</head>
<body>

<header class="bg-dark text-white p-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="fs-4"><i class="fas fa-car"></i> My Registered Cars</h1>
        <nav>
            <a href="my_cars.php" class="btn btn-outline-light btn-sm mx-1">Register New Car</a>
            <a href="logout.php" class="btn btn-danger btn-sm mx-1">Logout</a>
        </nav>
    </div>
</header>

<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <div class="row g-4">
            <?php while ($car = $result->fetch_assoc()):
                $photos = json_decode($car['photo'], true);
                $firstPhoto = isset($photos[0]) ? htmlspecialchars($photos[0]) : 'default.jpg';

                $color = htmlspecialchars($car['color']);
                $cssColor = (preg_match('/^#[a-f0-9]{3,6}$/i', $color) || in_array(strtolower($color), ['red','blue','green','black','white','gray','silver','yellow','orange','purple']))
                    ? $color : '#666';
                ?>
                <div class="col-md-4">
                    <div class="card car-card shadow-sm h-100">
                        <img src="<?= $firstPhoto ?>" class="card-img-top" alt="Car Image">
                        <div class="card-body text-center">

                                <i class="fas fa-car-side fa-3x text-white" style="opacity: 0.9;"></i>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($car['make']) . ' ' . htmlspecialchars($car['model']) ?></h5>
                            <p class="card-text mb-1"><strong>Year:</strong> <?= htmlspecialchars($car['year']) ?></p>
                            <p class="card-text mb-1">
                                <strong>Color:</strong>
                                <i class="fas fa-car" style="color: <?= htmlspecialchars($car['color']) ?>; font-size: 14px; margin-left: 5px;"></i>
                                <?= htmlspecialchars($car['color']) ?>
                            </p>
                            <p class="card-text mb-1"><strong>Fuel:</strong> <?= htmlspecialchars($car['fuel']) ?></p>
                            <p class="card-text mb-1"><strong>Gearbox:</strong> <?= htmlspecialchars($car['gearbox']) ?></p>
                            <p class="card-text mb-1"><strong>Reg. Nr.:</strong> <?= htmlspecialchars($car['registration_number']) ?></p>
                            <p class="card-text mb-1"><strong>VIN:</strong> <?= htmlspecialchars($car['vin']) ?></p>
                            <?php if (!empty($car['description'])): ?>
                                <p class="card-text"><strong>Description:</strong> <?= htmlspecialchars($car['description']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">You haven't registered any cars yet.</div>
    <?php endif; ?>
</div>

<footer class="text-center mt-5 mb-4">
    <p>&copy; 2025 Our Car Catalog. All rights reserved.</p>
</footer>

</body>
</html>
