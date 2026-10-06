document.getElementById('calculate-btn').addEventListener('click', function() {
    let sizeRadio = document.querySelector('input[name="size"]:checked');
    let sizePrice = sizeRadio ? parseFloat(sizeRadio.value) : 0;

    let toppingCheckboxes = document.querySelectorAll('input[name="topping"]:checked');
    let toppingPrice = 0;
    toppingCheckboxes.forEach(function(checkbox) {
        toppingPrice += parseFloat(checkbox.value);
    });

    let singlePizzaPrice = sizePrice + toppingPrice;

    let count = parseInt(document.getElementById('count').value);
    if (isNaN(count) || count < 1) count = 1;

    let city = document.getElementById('city').value;
    let totalPizzasPrice = 0;

    if (singlePizzaPrice > 0) {
        if (city === 'jelgava') {
            totalPizzasPrice = singlePizzaPrice + (singlePizzaPrice * 0.80 * (count - 1));
        } else if (city === 'dobele') {
            let paidPizzas = count - Math.floor(count / 3);
            totalPizzasPrice = singlePizzaPrice * paidPizzas;
        } else {
            totalPizzasPrice = singlePizzaPrice * count;
        }
    }

    let deliveryDropdown = document.getElementById('delivery').value;
    let deliveryPrice = deliveryDropdown ? parseFloat(deliveryDropdown) : 0;

    let finalTotal = totalPizzasPrice + deliveryPrice;

    if (sizePrice === 0) {
        finalTotal = 0;
    }
    document.getElementById('total-price').textContent = finalTotal.toFixed(2) + ' €';
});