<!-- Autors:Ervīns Jēkabsons; Koda daļa kas ļauj reģistrētiem lietotājiem reģsitrēt automašinas nu tās saglabā datubāze
Veidots 2024.gadā, jaunākā vesrija-->
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_registration";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle GET requests for make and color
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['type'])) {
    $type = $_GET['type'];

    if ($type === 'make') {
        $sqlMakes = "SELECT make FROM make_brand";
        $resultMakes = $conn->query($sqlMakes);
        if ($resultMakes->num_rows > 0) {
            while ($row = $resultMakes->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['make']) . "'>" . htmlspecialchars($row['make']) . "</option>";
            }
        } else {
            echo "<option value=''>No make available</option>";
        }
    } elseif ($type === 'color') {
        $sqlColors = "SELECT color FROM color";
        $resultColors = $conn->query($sqlColors);
        if ($resultColors->num_rows > 0) {
            while ($row = $resultColors->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['color']) . "'>" . htmlspecialchars($row['color']) . "</option>";
            }
        } else {
            echo "<option value=''>No colors available</option>";
        }
    }

    $conn->close();
    exit();
}

// Handle POST requests for car registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $make = $conn->real_escape_string($_POST['make']);
    $model = $conn->real_escape_string($_POST['model']);
    $year = $conn->real_escape_string($_POST['year']);
    $color = $conn->real_escape_string($_POST['color']);
    $fuel = $conn->real_escape_string($_POST['fuel']);
    $description = $conn->real_escape_string($_POST['description']);
    $gearbox = $conn->real_escape_string($_POST['gearbox']);
    $registration_number = $conn->real_escape_string($_POST['registration_number']);
    $vin = $conn->real_escape_string($_POST['vin']);

    // Handle multiple file uploads
    $photoPaths = [];
    if (isset($_FILES["photos"]) && !empty($_FILES["photos"]["name"][0])) {
        $targetDir = "../photo/";
        foreach ($_FILES["photos"]["name"] as $key => $fileName) {
            $targetFile = $targetDir . basename($fileName);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $check = getimagesize($_FILES["photos"]["tmp_name"][$key]);

            if ($check !== false && in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                if (move_uploaded_file($_FILES["photos"]["tmp_name"][$key], $targetFile)) {
                    $photoPaths[] = $targetFile; // Add path to photoPaths array
                } else {
                    echo "Failed to upload image: $fileName.";
                    exit();
                }
            } else {
                echo "File $fileName is not a valid image or format is incorrect.";
                exit();
            }
        }
    } else {
        echo "No images uploaded.";
        exit();
    }

    // Encode photo paths as JSON
    $photosJson = json_encode($photoPaths);

    // Check for duplicate VIN
    $stmt = $conn->prepare("SELECT vin FROM cars WHERE vin = ?");
    $stmt->bind_param("s", $vin);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Error: VIN already exists.";
    } else {
        // Insert car data into the database
        $stmt = $conn->prepare("INSERT INTO cars (make, model, year, color, fuel, gearbox, description, registration_number, vin, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $make, $model, $year, $color, $fuel, $gearbox, $description, $registration_number, $vin, $photosJson);

        if ($stmt->execute()) {
            echo "Car successfully registered!";
        } else {
            echo "Error: Could not save car data.";
        }
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>