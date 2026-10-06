<!-- Autors:Ervīns Jēkabsons; Galvenā mājas lapa kur ir redzami reģistrētie automobīļi
Veidots 2024.gadā, jaunākā vesrija-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Catalog</title>
    <link rel="stylesheet" href="../css/styles2.css">
    <link rel="icon" href="https://www.shutterstock.com/image-vector/automobile-check-mark-icon-vector-600nw-1414290941.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header class="bg-dark text-white p-3">
    <div class="container d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-car"></i> Car Catalog</h1>
        <nav>
            <a href="admin_login.php" class="btn btn-outline-light btn-sm mx-1">Admin</a>
            <a href="../html/login.html" class="btn btn-outline-light btn-sm mx-1">Login</a>
            <a href="../html/about.html" class="btn btn-outline-light btn-sm mx-1">About</a>
        </nav>
    </div>
    <div class="container mt-3">
        <input type="text" id="mySearch" onkeyup="filterCars()" class="form-control" placeholder="Search by make, model, or year">
        <p id="noResults" style="display:none; text-align:center; margin-top:20px;">No matching cars found.</p>
    </div>
</header>


<div class="container" id="carContainer">
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

    // Fetch car data
    $sql = "SELECT id, make, model, year, color, fuel, gearbox, photo FROM cars";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Decode photo data (JSON array) if applicable
            $photos = json_decode($row['photo'], true);

            // Check if photos are available and set the first photo as the main image
            $indexPhoto = !empty($photos) ? $photos[0] : 'default_image.jpg'; // Use a default image if no photos are found

            echo "<div class='car-item' data-make='" . htmlspecialchars($row['make']) . "' data-model='" . htmlspecialchars($row['model']) . "' data-year='" . htmlspecialchars($row['year']) . "'>";
            echo "<a href='../php/car_details.php?id=" . htmlspecialchars($row['id']) . "'>";

            // Display the first photo as the main image for the car
            echo "<img src='" . htmlspecialchars($indexPhoto) . "' alt='Car Photo'>";

            echo "<div class='car-info'>";
            echo "<h3>" . htmlspecialchars($row['make']) . " " . htmlspecialchars($row['model']) . "</h3>";
            echo "<p>Year: " . htmlspecialchars($row['year']) . "</p>";
            echo "<p>Gearbox:" . htmlspecialchars($row['gearbox']) . "</p>";
            echo "</div>";
            echo "</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No cars registered yet.</p>";
    }

    $conn->close();
    ?>
</div>

<script>
    let searchTimeout; // ← Add this line

    function filterCars() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
            const input = document.getElementById("mySearch").value.toUpperCase();
            const carItems = document.querySelectorAll(".car-item");
            let found = false;

            carItems.forEach(car => {
                const make = car.getAttribute("data-make").toUpperCase();
                const model = car.getAttribute("data-model").toUpperCase();
                const year = car.getAttribute("data-year").toUpperCase();

                if (make.includes(input) || model.includes(input) || year.includes(input)) {
                    car.style.display = "";
                    found = true;
                } else {
                    car.style.display = "none";
                }
            });

            document.getElementById("noResults").style.display = found ? "none" : "block";
        }, 300);
    }
</script>
</body>
<center>
    <p>&copy; 2025 Our Car Catalog. All rights reserved.</p>
</center>
</html>
