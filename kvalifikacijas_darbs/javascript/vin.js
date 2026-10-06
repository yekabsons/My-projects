document.addEventListener("DOMContentLoaded", function() {
    // Kad lapa ir ielādēta, tiek pievienoti notikumu klausītāji
    document.getElementById("carForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Neļauj formai tikt iesniegtai tradicionālā veidā (ar lapas pārlādi)

        var formData = new FormData(this); // Savāc formas datus, izmantojot FormData objektu

        // Veic AJAX pieprasījumu, lai nosūtītu formas datus uz serveri
        fetch("register_car.php", { // Pieprasa serverim ar POST metodi
            method: "POST",
            body: formData
        })
            .then(response => response.text()) // Pārveido atbildi uz tekstu
            .then(result => {
                // Pārbauda atbildi no servera
                if (result === "success") {
                    alert("Jūsu automašīnas dati ir veiksmīgi reģistrēti!"); // Veiksmīga reģistrācija
                    document.getElementById("carForm").reset(); // Atiestata formu
                } else if (result === "duplicate_vin") {
                    alert("Kļūda: VIN numurs jau eksistē sistēmā."); // VIN numurs ir dublikāts
                } else {
                    alert("Kļūda: " + result); // Vispārēja kļūda ar servera atgriezto informāciju
                }
            })
            .catch(error => {
                console.error("Kļūda:", error); // Izvada kļūdu konsolē
                alert("Radās kļūda, apstrādājot jūsu pieprasījumu."); // Ziņo par tīkla kļūdu
            });
    });

    // VIN ievades lauka klausītājs
    document.getElementById("vin").addEventListener("input", function() {
        this.value = this.value.toUpperCase(); // Pārveido VIN ievadi uz lielajiem burtiem
    });
});
