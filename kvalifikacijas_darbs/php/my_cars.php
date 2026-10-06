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

$brandsResult = $conn->query("SELECT make FROM make_brand ORDER BY make ASC");
$colorsResult = $conn->query("SELECT color FROM color ORDER BY color ASC");

$success = $error = "";
$formData = $_POST ?? [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $make = $_POST['make'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $color = $_POST['color'] ?? '';
    $fuel = $_POST['fuel'] ?? '';
    $gearbox = $_POST['gearbox'] ?? '';
    $registration_number = $_POST['registration_number'] ?? '';
    $vin = strtoupper(trim($_POST['vin'] ?? ''));
    $description = $_POST['description'] ?? '';
    $user_id = $_SESSION['user_id'];

    // Check VIN uniqueness
    $vinCheck = $conn->prepare("SELECT id FROM cars WHERE vin = ?");
    $vinCheck->bind_param("s", $vin);
    $vinCheck->execute();
    $vinCheck->store_result();

    if ($vinCheck->num_rows > 0) {
        $error = "VIN already exists. Please enter a unique VIN.";
        $formData['vin'] = "";
    } else {
        // Photo validation
        $photoPaths = [];
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $photoCount = count($_FILES['photos']['name']);
        $totalSize = array_sum($_FILES['photos']['size']);

        if ($photoCount > 20) {
            $error = "Maksimālais bilžu skaits ir 20.";
        } elseif ($totalSize > 100 * 1024 * 1024) {
            $error = "Kopējais bilžu apjoms nedrīkst pārsniegt 100 MB.";
        } else {
            foreach ($_FILES['photos']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = basename($_FILES['photos']['name'][$key]);
                    $targetFilePath = $uploadDir . time() . "_" . $fileName;
                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        $photoPaths[] = $targetFilePath;
                    }
                }
            }

            $photoJson = json_encode($photoPaths);

            $stmt = $conn->prepare("INSERT INTO cars (user_id, make, model, year, color, fuel, gearbox, registration_number, vin, description, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ississsssss", $user_id, $make, $model, $year, $color, $fuel, $gearbox, $registration_number, $vin, $description, $photoJson);

            if ($stmt->execute()) {
                $success = "Car registered successfully!";
                $formData = [];
            } else {
                $error = "Failed to register car. Try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Car</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<header class="bg-dark text-white p-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="fs-4"><i class="fas fa-plus-circle"></i> Register Car</h1>
        <nav>
            <a href="car_data.php" class="btn btn-outline-light btn-sm mx-1">My Cars</a>
            <a href="../html/about.html" class="btn btn-outline-light btn-sm mx-1">About</a>
            <a href="logout.php" class="btn btn-danger btn-sm mx-1">Logout</a>
        </nav>
    </div>
</header>

<div class="container">
    <div class="form-section">
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Brand</label>
                    <select name="make" class="form-select" required>
                        <option value="">Select Brand</option>
                        <?php while ($row = $brandsResult->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['make']) ?>" <?= ($formData['make'] ?? '') === $row['make'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['make']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" required value="<?= htmlspecialchars($formData['model'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Year</label>
                    <input type="number" name="year" class="form-control" required min="1886" max="<?= date('Y') ?>" value="<?= htmlspecialchars($formData['year'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Color</label>
                    <select name="color" class="form-select" required>
                        <option value="">Select Color</option>
                        <?php while ($row = $colorsResult->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['color']) ?>" <?= ($formData['color'] ?? '') === $row['color'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['color']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fuel</label>
                    <select name="fuel" class="form-select" required>
                        <?php foreach (['Petrol', 'Diesel', 'Electric', 'Hybrid'] as $fuel): ?>
                            <option value="<?= $fuel ?>" <?= ($formData['fuel'] ?? '') === $fuel ? 'selected' : '' ?>><?= $fuel ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gearbox</label>
                    <select name="gearbox" class="form-select" required>
                        <option value="Manual" <?= ($formData['gearbox'] ?? '') === 'Manual' ? 'selected' : '' ?>>Manual</option>
                        <option value="Automatic" <?= ($formData['gearbox'] ?? '') === 'Automatic' ? 'selected' : '' ?>>Automatic</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Registration Number</label>
                    <input type="text" name="registration_number" class="form-control"="<?= htmlspecialchars($formData['registration_number'] ?? '') ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">VIN</label>
                    <input type="text" name="vin" class="form-control text-uppercase" maxlength="17" oninput="this.value = this.value.toUpperCase();" required value="<?= htmlspecialchars($formData['vin'] ?? '') ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Upload Car Photo(s)</label>
                    <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success btn-sm w-100"><i class="fas fa-check-circle"></i> Register Car</button>
                </div>
            </div>
        </form>
    </div>
</div>

<footer class="text-center mt-5 mb-4">
    <p>&copy; 2025 Our Car Catalog. All rights reserved.</p>
</footer>

</body>
</html>