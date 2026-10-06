document.addEventListener("DOMContentLoaded", function () {
    // Fetch the car makes
    fetch("../php/register_car.php?type=make", {
        method: "GET"
    })
        .then(response => response.text())
        .then(options => {
            document.getElementById("make").innerHTML += options; // Populate the select with car makes
        })
        .catch(error => {
            console.error("Error fetching car makes:", error);
        });

    // Fetch the car colors
    fetch("../php/register_car.php?type=color", {
        method: "GET"
    })
        .then(response => response.text())
        .then(options => {
            document.getElementById("color").innerHTML += options; // Populate the select with car colors
        })
        .catch(error => {
            console.error("Error fetching car colors:", error);
        });
});


document.getElementById("carForm").addEventListener("submit", function(event) {
    event.preventDefault();

    const formData = new FormData(this);

    // Send the form data using an AJAX request

});

