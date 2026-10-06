<!-- Autors:Ervīns Jēkabsons; JS funkcija kura spēj lietotājiem reģistrēt automašīnas
Veidots 2024.gadā, jaunākā vesrija-->
// Listen for form submission
document.getElementById('carForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Get form data
    const carMake = document.getElementById('carMake').value;
    const carPrice = document.getElementById('carPrice').value;
    const carFuel = document.getElementById('carFuel').value;
    const carColor = document.getElementById('carColor').value;
    const carVin = document.getElementById('carVin').value;
    const carMileage = document.getElementById('carMileage').value;

    // Handle multiple image upload
    const carImages = document.getElementById('carImages').files;
    let imageUrls = [];

    for (let i = 0; i < carImages.length; i++) {
        const imageUrl = URL.createObjectURL(carImages[i]);
        imageUrls.push(imageUrl);
    }

    // Create a car object to store
    const newCar = {
        make: carMake,
        price: carPrice,
        fuel: carFuel,
        color: carColor,
        vin: carVin,
        mileage: carMileage,
        images: imageUrls
    };

    // Save the car in localStorage
    let cars = JSON.parse(localStorage.getItem('cars')) || [];
    cars.push(newCar);
    localStorage.setItem('cars', JSON.stringify(cars));

    alert('Car registered successfully!');
    window.location.href = '../php/index.php';  // Redirect to the catalog page
});
