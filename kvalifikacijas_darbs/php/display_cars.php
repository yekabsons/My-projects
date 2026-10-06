<!-- Autors:Ervīns Jēkabsons; Šī koda daļa ļauj attēlot auto un sākum ekrāna
Veidots 2024.gadā, jaunākā vesrija-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Cars</title>
    <link rel="stylesheet" href="../css/styles2.css">
</head>
<body>
<h2>Registered Cars</h2>
<table>
    <thead>
    <tr>
        <th>Make</th>
        <th>Model</th>
        <th>Year</th>
        <th>Color</th>
        <th>Fuel</th>
        <th>Gearbox</th>
        <th>Price</th>
        <th>Photo</th>
    </tr>
    </thead>
    <tbody>
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
    $sql = "SELECT make, model, year, color, fuel, gearbox, price, photo FROM cars";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['make']) . "</td>";
            echo "<td>" . htmlspecialchars($row['model']) . "</td>";
            echo "<td>" . htmlspecialchars($row['year']) . "</td>";
            echo "<td>" . htmlspecialchars($row['color']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fuel']) . "</td>";
            echo "<td>" . htmlspecialchars($row['gearbox']) . "</td>";
            echo "<td>" . htmlspecialchars($row['price']) . "</td>";
            echo "<td><img src='" . htmlspecialchars($row['photo']) . "' alt='Car Photo' style='width:100px;'></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No cars registered yet.</td></tr>";
    }

    $conn->close();
    ?>
    </tbody>
</table>
</body>
</html>