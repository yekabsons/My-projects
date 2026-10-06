function setGreeting() {
    const hour = new Date().getHours();
    let greetingText = "";

    if (hour >= 6 && hour < 12) {
        greetingText = "Labrīt!";
    } else if (hour >= 12 && hour < 18) {
        greetingText = "Labdien!";
    } else if (hour >= 18 && hour < 24) {
        greetingText = "Labvakar!";
    } else {
        greetingText = "Sveiki naktī!";
    }

    document.getElementById("greeting").innerText = greetingText;
}

setGreeting();

document.getElementById('regForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const vards = document.getElementById('vards');
    const epasts = document.getElementById('epasts');
    const parole = document.getElementById('parole');
    const parole2 = document.getElementById('parole2');
    const valsts = document.getElementById('valsts');
    const noteikumi = document.getElementById('noteikumi');

    const dz_virietis = document.getElementById('dz_virietis');
    const dz_sieviete = document.getElementById('dz_sieviete');
    const dzimumsError = document.getElementById('dzimums-error');

    let isValid = true;

    function checkField(condition, field) {
        if (condition) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            isValid = false;
        }
    }

    checkField(vards.value.trim() !== "", vards);

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    checkField(emailRegex.test(epasts.value), epasts);

    const passwordRegex = /^(?=.*\d).{6,}$/;
    checkField(passwordRegex.test(parole.value), parole);

    checkField(parole2.value === parole.value && parole2.value !== "", parole2);

    checkField(valsts.value !== "", valsts);

    if (dz_virietis.checked || dz_sieviete.checked) {
        dzimumsError.style.display = "none";
    } else {
        dzimumsError.style.display = "block";
        isValid = false;
    }

    checkField(noteikumi.checked, noteikumi);

    if (isValid) {
        alert("Forma veiksmīgi aizpildīta un nosūtīta!");
    }
});