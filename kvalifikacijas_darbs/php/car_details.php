<!-- Autors:Ervīns Jēkabsons; Šī daļa atrodas uz galvenajā mājas lapā,
ši funkcija darbojas tad kad tu uzpied uz konkrēto auto kartīti, un tad lietotājam
parādās vairāk bildes par auto uz kura lietotājs ir uzpiedis un var apskatīt auto informāciju
kuru iepriekšējais lietotājs bija ievadījis
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

if (isset($_GET['id'])) {
    $car_id = intval($_GET['id']); // Get car ID from the URL
    $stmt = $conn->prepare("SELECT make, model, year, color, fuel, gearbox, photo, vin, registration_number, description FROM cars WHERE id = ?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $car = $result->fetch_assoc();

        // Decode JSON-encoded photos
        $photos = json_decode($car['photo'], true);

        if (empty($photos)) {
            $photos = [];
        }
    } else {
        die("Car not found.");
    }

    $stmt->close();
} else {
    die("Invalid car ID.");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></title>
    <link rel="icon" href="https://www.shutterstock.com/image-vector/automobile-check-mark-icon-vector-600nw-141429094.jpg" type="image/x-icon"/>
    <link rel="stylesheet" href="../css/style3.css">
</head>
<body>
<header>
    <h1>Car Details</h1>
    <a href="index.php">Back to Catalog</a>
</header>
<div class="container">
    <div class="slideshow-container">
        <?php
        if (is_array($photos) && !empty($photos)) {
            foreach ($photos as $index => $photo) {
                echo '<img src="' . htmlspecialchars($photo) . '" alt="Car Photo" class="slides" onclick="toggleModal(this.src)">';
            }
        } else {
            echo '<p>No photos available.</p>';
        }
        ?>
        <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
        <a class="next" onclick="changeSlide(1)">&#10095;</a>
        <span class="slide-number" id="slide-number"></span>
    </div>

    <h2><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></h2>
    <p><strong>Year:</strong> <?php echo htmlspecialchars($car['year']); ?></p>
    <p><strong>Color:</strong> <?php echo htmlspecialchars($car['color']); ?></p>
    <p><strong>Fuel:</strong> <?php echo htmlspecialchars($car['fuel']); ?></p>
    <p><strong>Gearbox:</strong> <?php echo htmlspecialchars($car['gearbox']); ?></p>
    <p><strong>VIN:</strong> <?php echo htmlspecialchars($car['vin']); ?></p>
    <p><strong>Registration number:</strong> <?php echo htmlspecialchars($car['registration_number']); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($car['description']); ?></p>
</div>

<!-- Modal for Enlarged Image -->
<div id="imageModal" class="modal">
    <span class="close" onclick="toggleModal()">&times;</span> <!-- Existing close button -->
    <button class="close-button" onclick="toggleModal()">X</button> <!-- New Windows-style button -->
    <img class="modal-content" id="modalImage">
</div>
<script>
    let slideIndex = 0;
    showSlides(slideIndex);

    function changeSlide(n) {
        showSlides(slideIndex += n);
    }
<!--SLide show-->
    function showSlides(n) {
        let slides = document.getElementsByClassName("slides");
        let slideNumber = document.getElementById("slide-number");
        if (n >= slides.length) { slideIndex = 0; }
        if (n < 0) { slideIndex = slides.length - 1; }
        for (let i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slides[slideIndex].style.display = "block";
        slideNumber.innerHTML = (slideIndex + 1) + " / " + slides.length;
    }

    let isDragging = false;
    let startX, startY;

    function toggleModal(src = null) {
        const modal = document.getElementById("imageModal");
        const modalImage = document.getElementById("modalImage");

        if (modal.style.display === "flex") {
            modal.style.display = "none";
        } else {
            modal.style.display = "flex";
            if (src) {
                modalImage.src = src;
                modalImage.style.left = "0"; // Reset position
                modalImage.style.top = "0";
            }
        }
    }

    // Get modal and image elements
    const modalImage = document.getElementById("modalImage");
    const modal = document.getElementById("imageModal");

    // Mouse down - start dragging
    modalImage.addEventListener('mousedown', (e) => {
        e.preventDefault();
        isDragging = true;
        startX = e.pageX - modalImage.offsetLeft;
        startY = e.pageY - modalImage.offsetTop;
        modalImage.style.cursor = 'grabbing';
    });

    // Mouse move - handle dragging
    modalImage.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - startX;
        const y = e.pageY - startY;
        modalImage.style.left = `${x}px`;
        modalImage.style.top = `${y}px`;
    });

    // Mouse up - stop dragging
    modalImage.addEventListener('mouseup', () => {
        isDragging = false;
        modalImage.style.cursor = 'grab';
    });

    // Mouse leave - stop dragging if mouse leaves image
    modalImage.addEventListener('mouseleave', () => {
        isDragging = false;
        modalImage.style.cursor = 'grab';
    });

    // Double-click event to close the modal
    modal.addEventListener('dblclick', () => {
        modal.style.display = "none";
    });

    // Prevent single click from closing modal unless clicked outside
    modal.addEventListener('click', (e) => {
        if (e.target.id === "imageModal") toggleModal();
    });

    // Add event listener for the close button
    const closeButton = document.querySelector('.close-button');
    closeButton.addEventListener('click', () => {
        toggleModal();
    });
</script>
</body>
</html>