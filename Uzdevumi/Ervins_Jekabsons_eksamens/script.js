document.addEventListener("DOMContentLoaded", function() {

    const vetItems = document.querySelectorAll('.vet-item');
    vetItems.forEach(item => {
        item.addEventListener('click', function() {
            const mapId = this.getAttribute('data-target');
            const mapContainer = document.getElementById(mapId);

            if (mapContainer.style.display === "block") {
                mapContainer.style.display = "none";
            } else {
                mapContainer.style.display = "block";
            }
        });
    });

    const form = document.getElementById("regForm");
    const submitBtn = document.getElementById("submitBtn");
    const regModal = document.getElementById('regModal');

    if (regModal) {
        regModal.addEventListener('hidden.bs.modal', function () {
            form.reset();
        });
    }

    if (submitBtn) {
        submitBtn.addEventListener('click', function(event) {
            const vards = document.getElementById('vards').value;
            const username = document.getElementById('lietotajvards').value;
            const password = document.getElementById('parole').value;
            const dzimumsSelected = document.querySelector('input[name="dzimums"]:checked');

            if (!vards || !username || !password || !dzimumsSelected) {
                return;
            }

            event.preventDefault();

            if (username.length <= 8) {
                alert("Ievadīto simbolu skaitam jābūt lielākam par 8! (Lietotājvārdā vajag vismaz 9 simbolus)");
                return;
            }

            const hasLetter = /[A-Za-z]/.test(password);
            const hasDigit = /\d/.test(password);

            if (password.length < 8 || !hasLetter || !hasDigit) {
                alert("Paroles laukam jābūt vismaz 8 simboliem un jāsatur gan burtus, gan ciparus!");
                return;
            }

            alert("Reģistrācija veiksmīga!");

            const modalElement = document.getElementById('regModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            modalInstance.hide();

            // Notīra formu
            form.reset();
        });
    }
});