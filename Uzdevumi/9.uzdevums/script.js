function raditPazinojumu() {
    alert("Paziņojums Logs");
}

function ievaditVardu() {
    let vards = prompt("Ievadiet savu vārdu", "Vārds");
    if (vards !== null && vards.trim() !== "") {
        document.getElementById("tekstaIzvade").innerHTML = "Sveiki, " + vards + " !";
    }
}

function ievaditSkaitli() {
    let ievade = prompt("Ievadiet skaitli no 0 līdz 100!", "50");
    let izvadesLauks = document.getElementById("skaitlaIzvade");

    if (ievade !== null && ievade.trim() !== "") {
        let skaitlis = parseInt(ievade);
        if (isNaN(skaitlis)) {
            izvadesLauks.innerHTML = "Lūdzu, ievadiet derīgu skaitli!";
            return;
        }
        if (skaitlis < 0) {
            izvadesLauks.innerHTML = "Ievadītais skaitlis ir mazāks par 0!";
        } else if (skaitlis > 100) {
            izvadesLauks.innerHTML = "Ievadītais skaitlis ir lielāks par 100!";
        } else if (skaitlis % 2 === 0) {
            izvadesLauks.innerHTML = "Pāra skaitlis";
        } else {
            izvadesLauks.innerHTML = "Nepāra skaitlis";
        }
    }
}

function mainitBodyFonu(krasa) {
    document.body.style.backgroundColor = krasa;
}

function mainitBlokaFonu(krasa) {
    let bloks = document.getElementById("hoverBloks");
    let virsraksts = document.getElementById("hoverVirsraksts");

    bloks.style.backgroundColor = krasa;
    virsraksts.style.color = "white";
}

function atjaunotBlokaFonu() {
    let bloks = document.getElementById("hoverBloks");
    let virsraksts = document.getElementById("hoverVirsraksts");

    bloks.style.backgroundColor = "";
    virsraksts.style.color = "";
}